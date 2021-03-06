<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class InvasionController extends Controller
    {
        public function index()
        {
            $user_id = session()->get('user_id');
            $user_race = session()->get('user_race');
            if ($user_race === null)
            {
                $user_race = DB::table('users')
                ->where('id', '=', $user_id)
                ->value('race');
                session()->put(['user_race' => $user_race]);
            }
            $city_id = session()->get('city_id');
            if ($city_id === null)
            {
                $city_id = DB::table('cities')
                ->where('owner', '=', $user_id)
                ->where('is_capital', '=', 1)
                ->value('id');
                session()->put(['city_id' => $city_id]);
            }
            $is_admin = DB::table('users')->where('id', '=', $user_id)->value("is_admin");
            $util = Common::get_utilities($user_id, $city_id);
            $city_unit = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $units = DB::table('units')->select('name', 'storage')->get();
            $mercenaries = DB::table('mercenaries')->select('name', 'storage')->get();
            $city_mercenaries = DB::table('cities_mercenaries')->where('city_id', '=', $city_id)->first();
            $city_items = DB::table('cities')->select('basic_shield', 'basic_armor', 'basic_sword', 'basic_spear', 'basic_bow')->where('id', '=', $city_id)->first();
            $info_unit = [];
            foreach ($city_unit as $unit => $val)
            {
                if ($unit == "id" || $unit == "city_id" || $unit == "owner")
                    continue;
                if ($val <= 0)
                    continue ;
                array_push($info_unit, ["ref" => $unit, "name" => trans("unit." . $unit), "quantity" => $val, "storage" => $this->get_unit_storage($units, $unit)]);
            }
            foreach ($mercenaries as $mercenary)
            {
                $mercenary_name = $mercenary->name;
                $mercenary_qantity = $city_mercenaries->$mercenary_name;
                if ($mercenary_qantity > 0)
                    array_push($info_unit, ["ref" => $mercenary_name, "name" => trans('mercenaries.' . $mercenary_name), "quantity" => $mercenary_qantity, "storage" => $mercenary->storage]);
            }
            $info_item = [];
            foreach ($city_items as $item => $quantity)
            {
                if ($quantity <= 0)
                    continue ;
                $info_item[$item] = ["ref" => $item, "name" => trans('item.' . $item), "quantity" => $quantity];
            }
            $user_cities = DB::table('cities')->select('name')->where('owner', '=', $user_id)->where('id', '!=', $city_id)->get();
            $res = ["food" => $util->food, "wood" => $util->wood, "rock" => $util->rock, "steel" => $util->steel, "gold" => $util->gold];
            $attackable_cities = Common::get_targetable_cities($city_id, $user_id, $util->x_pos, $util->y_pos);
            $limits = $this->get_limits($city_id);
            return view('invasion', compact('is_admin', 'res', 'util', 'info_unit', 'info_item', 'user_cities', 'attackable_cities', 'limits'));
        }

        private function get_limits($city_id)
        {
            $limits = ["attack" => 0, "move" => 0];
            $traveling_units = DB::table('traveling_units')->where('city_id', '=', $city_id)->where('mission', '=', 5)->orWhere('mission', '=', 7)->get();
            foreach ($traveling_units as $travel)
            {
                if ($travel->mission == 5)
                    $limits["attack"]++;
                else
                    $limits["move"]++;
            }
            return $limits;
        }

        private function get_unit_storage($units, $name)
        {
            foreach ($units as $unit)
            {
                if ($unit->name == $name)
                    return ($unit->storage);
            }
            return (0);
        }

        public function calculate_move_units(Request $request)
        {
            $units = $request['units'];
            $units = explode(",", preg_replace('/[{}\"]/', '', $units));
            $tab = [];
            foreach ($units as $key)
            {
                if (strstr($key, ":") == false)
                    continue ;
                $ex = explode(":", $key);
                if (!is_numeric($ex[1]))
                    return ("Invasion error : bad format unit");
                $tab[$ex[0]] = intval($ex[1]);
            }
            if (isset($request['res']))
            {
                $res = $request['res'];
                $res = explode(",", preg_replace('/[{}\"]/', '', $res));
                $tab_res = [];
                foreach ($res as $key)
                {
                    if (strstr($key, ":") == false)
                        continue ;
                    $ex = explode(":", $key);
                    if (!is_numeric($ex[1]))
                        return ("Invasion error : bad format unit");
                    $tab_res[$ex[0]] = intval($ex[1]);
                }
            }
            else
                $tab_res = null;
            $city_target = $request['city_target'];
            $user_id = session()->get('user_id');
            $city_id = session()->get('city_id');
            $city_target_info = DB::table('cities')->where('name', '=', $city_target)->where('owner', '=', $user_id)->first();
            if ($city_target_info == null || $city_target_info->id == $city_id)
                return ("invasion error : bad city");
            $city_units = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $min_speed = -1;
            $current_city = DB::table('cities')->where('id', '=', $city_id)->first();
            $fret = 0;
            $storage = 0;
            if ($tab_res != null)
            {
                foreach ($tab_res as $res => $val)
                {
                    if ($val <= 0)
                        continue ;
                    if (!isset($current_city->$res) || $current_city->$res < $val)
                        return ("invasion error : bad res or item");
                    else
                        $fret += $val;
                }
            }
            foreach ($tab as $unit => $quantity)
            {
                if ($quantity <= 0)
                    continue;
                if (!isset($city_units->$unit) || $city_units->$unit < $quantity)
                    return ("invasion error : bad unit");
                $unit_infos = DB::table('units')->select('speed', 'storage')->where('name', '=', $unit)->first();
                if ($unit_infos == null)
                    $unit_infos = DB::table('mercenaries')->select('speed', 'storage')->where('name', '=', $unit)->first();
                if ($min_speed == -1 || $unit_infos->speed < $min_speed)
                    $min_speed = $unit_infos->speed;
                $storage += ($unit_infos->storage * $quantity);
            }
            if ($fret > $storage)
                return ("invasion error : fret > storage");
            $city_coord = DB::table('cities')->select('x_pos', 'y_pos')->where('id', '=', $city_id)->first();
            $travel_duration = Common::sec_to_date(((abs($city_coord->x_pos - $city_target_info->x_pos) + abs($city_coord->y_pos - $city_target_info->y_pos)) * (3600 / $min_speed)));
            return (trans('invasion.travel_duration') . " " . $travel_duration . " ");
        }

        public function move_units(Request $request)
        {
            $units = $request['units'];
            $units = explode(",", preg_replace('/[{}\"]/', '', $units));
            $tab = [];
            foreach ($units as $key)
            {
                if (strstr($key, ":") == false)
                    continue ;
                $ex = explode(":", $key);
                if (!is_numeric($ex[1]))
                    return ("Invasion error : bad format unit");
                $tab[$ex[0]] = intval($ex[1]);
            }
            if (isset($request['res']))
            {
                $res = $request['res'];
                $res = explode(",", preg_replace('/[{}\"]/', '', $res));
                $tab_res = [];
                foreach ($res as $key)
                {
                    if (strstr($key, ":") == false)
                        continue ;
                    $ex = explode(":", $key);
                    if (!is_numeric($ex[1]))
                        return ("Invasion error : bad format unit");
                    $tab_res[$ex[0]] = intval($ex[1]);
                }
            }
            else
                $tab_res = null;
            $city_target = $request['city_target'];
            $user_id = session()->get('user_id');
            $city_id = session()->get('city_id');
            $city_target_info = DB::table('cities')->where('name', '=', $city_target)->where('owner', '=', $user_id)->first();
            if ($city_target_info == null || $city_target_info->id == $city_id)
                return ("invasion error : bad city");
            $city_units = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $min_speed = -1;
            $current_city = DB::table('cities')->where('id', '=', $city_id)->first();
            $fret = 0;
            $storage = 0;
            $res_send = null;
            if ($tab_res != null)
            {
                $update_res_tab = [];
                foreach ($tab_res as $res => $val)
                {
                    if ($val <= 0)
                        continue ;
                    if (!isset($current_city->$res) || $current_city->$res < $val)
                        return ("invasion error : bad res or item");
                    else
                    {
                        $fret += $val;
                        if ($res_send == null)
                            $res_send = $res . ":" . $val;
                        else
                            $res_send .= ";" . $res . ":" . $val;
                        $update_res_tab[$res] = $current_city->$res - $val;
                    }
                }
            }
            $units_send = "";
            $update_units_tab = [];
            foreach ($tab as $unit => $quantity)
            {
                if ($quantity <= 0)
                    continue;
                if ($city_units->$unit < $quantity)
                    return ("invasion error : bad unit");
                $unit_infos = DB::table('units')->select('id', 'speed', 'storage')->where('name', '=', $unit)->first();
                if ($unit_infos == null)
                    return ("invasion error : unknow unit");
                if ($min_speed == -1 || $unit_infos->speed < $min_speed)
                    $min_speed = $unit_infos->speed;
                if ($units_send == "")
                    $units_send .= $unit_infos->id . ":" . $quantity;
                else
                    $units_send .= ";" . $unit_infos->id . ":" . $quantity;
                $update_units_tab[$unit] = $city_units->$unit - $quantity;
                $storage += ($unit_infos->storage * $quantity);
            }
            if ($fret > $storage)
                return ("invasion error : fret > storage");
            $city_coord = DB::table('cities')->select('x_pos', 'y_pos')->where('id', '=', $city_id)->first();
            $travel_duration = ((abs($city_coord->x_pos - $city_target_info->x_pos) + abs($city_coord->y_pos - $city_target_info->y_pos)) * (3600 / $min_speed));
            if ($travel_duration < 0)
                return ("invasion error : bad travel");
            $finishing_date = $travel_duration + time();
            $starting_point = $city_coord->x_pos . "/" . $city_coord->y_pos;
            $ending_point = $city_target_info->x_pos . "/" . $city_target_info->y_pos;
            DB::table('traveling_units')->insert(["city_id" => $city_id, "owner" => $user_id, "starting_point" => $starting_point, "ending_point" => $ending_point, "units" => $units_send, "res_taken" => $res_send, "traveling_duration" => $travel_duration, "finishing_date" => $finishing_date, "mission" => 7]);
            DB::table('cities_units')->where('city_id', '=', $city_id)->update($update_units_tab);
            if ($res_send != null)
                DB::table('cities')->where('id', '=', $city_id)->update($update_res_tab);
            return ("good");
        }

        public function calculate_attack(Request $request)
        {
            $target_city = null;
            $user_id = session()->get('user_id');
            $city_id = session()->get('city_id');
            if (isset($request['target_city']))
            {
                $target_city = DB::table('cities')->where('name', '=', $request['target_city'])->first();
                if ($target_city == null)
                    return ("Invasion error : city not found");
                else if ($target_city->owner == $user_id)
                    return ("Invasion error : cannot attack allied");
            }
            else if (isset($request['x_pos']) && isset($request['y_pos']))
            {
                if ($request['x_pos'] < -2000 || $request['x_pos'] > 2000 || $request['y_pos'] < -2000 || $request['y_pos'] > 2000 || !is_numeric($request['x_pos']) || !is_numeric($request['y_pos']))
                    return "Invasion error : bad coord";
                $target_city = DB::table('cities')->where('x_pos', '=', $request['x_pos'])->where('y_pos', '=', $request['y_pos'])->first();
                if ($target_city != null && $target_city->owner == $user_id)
                    return ("Invasion error : cannot attack allied");
            }
            else
                return "Invasion error : missing data";
            $infos = [];
            $user_city = DB::table('cities')
            ->join('cities_buildings', 'cities.id', '=', 'cities_buildings.city_id')
            ->select('cities.x_pos', 'cities.y_pos', 'cities_buildings.Cartographe')
            ->where('cities.id', '=', $city_id)
            ->first();
            if ($target_city == null && $request['x_pos'] >= $user_city->x_pos - $user_city->Cartographe && $request['x_pos'] <= $user_city->x_pos + $user_city->Cartographe
                    && $request['y_pos'] >= $user_city->y_pos - $user_city->Cartographe && $request['y_pos'] <= $user_city->y_pos + $user_city->Cartographe)
            {
                $cell_type = DB::table('map')->where('x_pos', '=', $request['x_pos'])->where('y_pos', '=', $request['y_pos'])->value('type');
                if ($cell_type == null)
                    $infos['cell'] = trans('map.empty');
                else
                    $infos['cell'] = trans('map.' . $cell_type);
            }
            else if ($target_city == null)
                $infos['cell'] = 'unknow';
            else if ($target_city != null && $target_city->x_pos >= $user_city->x_pos - $user_city->Cartographe && $target_city->x_pos <= $user_city->x_pos + $user_city->Cartographe
                    && $target_city->y_pos >= $user_city->y_pos - $user_city->Cartographe && $target_city->y_pos <= $user_city->y_pos + $user_city->Cartographe)
            {
                $infos['cell'] = trans('map.city');
                $infos['name'] = $target_city->name;
            }
            else
                $infos['cell'] = 'unknow';
            $x_pos = null;
            $y_pos = null;
            if ($target_city == null)
            {
                $x_pos = $request['x_pos'];
                $y_pos = $request['y_pos'];
            }
            else
            {
                $x_pos = $target_city->x_pos;
                $y_pos = $target_city->y_pos;
            }
            $units = $request['units'];
            $units = explode(",", preg_replace('/[{}\"]/', '', $units));
            $tab = [];
            foreach ($units as $key)
            {
                if (strstr($key, ":") == false)
                    continue ;
                $ex = explode(":", $key);
                if (!is_numeric($ex[1]))
                    return ("Invasion error : bad format unit");
                $tab[$ex[0]] = intval($ex[1]);
            }
            $city_units = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $min_speed = -1;
            $units_send = "";
            foreach ($tab as $unit => $quantity)
            {
                if ($quantity <= 0)
                    continue;
                if ($city_units->$unit < $quantity)
                    return ("invasion error : bad unit");
                $unit_infos = DB::table('units')->select('id', 'speed')->where('name', '=', $unit)->first();
                if ($unit_infos == null)
                    return ("invasion error : unknow unit");
                if ($min_speed == -1 || $unit_infos->speed < $min_speed)
                    $min_speed = $unit_infos->speed;
                if ($units_send == "")
                    $units_send .= $unit_infos->id . ":" . $quantity;
                else
                    $units_send .= ";" . $unit_infos->id . ":" . $quantity;
            }
            $travel_duration = Common::sec_to_date((abs($user_city->x_pos - $x_pos) + abs($user_city->y_pos - $y_pos)) * (3600 / $min_speed));
            $infos['travel_duration'] = trans('invasion.travel_duration') . $travel_duration . " ";
            $infos['x'] = $x_pos;
            $infos['y'] = $y_pos;
            return $infos;
        }

        public function attack(Request $request)
        {
            $target_city = null;
            $user_id = session()->get('user_id');
            $city_id = session()->get('city_id');
            if (isset($request['target_city']))
            {
                $target_city = DB::table('cities')->where('name', '=', $request['target_city'])->first();
                if ($target_city == null)
                    return ("Invasion error : city not found");
                else if ($target_city->owner == $user_id)
                    return ("Invasion error : cannot attack allied");
            }
            else if (isset($request['x_pos']) && isset($request['y_pos']))
            {
                if ($request['x_pos'] < -2000 || $request['x_pos'] > 2000 || $request['y_pos'] < -2000 || $request['y_pos'] > 2000 || !is_numeric($request['x_pos']) || !is_numeric($request['y_pos']))
                    return "Invasion error : bad coord";
                $target_city = DB::table('cities')->where('x_pos', '=', $request['x_pos'])->where('y_pos', '=', $request['y_pos'])->first();
                if ($target_city != null && $target_city->owner == $user_id)
                    return ("Invasion error : cannot attack allied");
            }
            else
                return "Invasion error : missing data";
            $x_pos = null;
            $y_pos = null;
            if ($target_city == null)
            {
                $x_pos = $request['x_pos'];
                $y_pos = $request['y_pos'];
            }
            else
            {
                $x_pos = $target_city->x_pos;
                $y_pos = $target_city->y_pos;
            }
            $units = $request['units'];
            $units = explode(",", preg_replace('/[{}\"]/', '', $units));
            $tab = [];
            foreach ($units as $key)
            {
                if (strstr($key, ":") == false)
                    continue ;
                $ex = explode(":", $key);
                if (!is_numeric($ex[1]))
                    return ("Invasion error : bad format unit");
                $tab[$ex[0]] = intval($ex[1]);
            }
            $city_units = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $min_speed = -1;
            $units_send = "";
            $update_units_tab = [];
            foreach ($tab as $unit => $quantity)
            {
                if ($quantity <= 0)
                    continue;
                if ($city_units->$unit < $quantity)
                    return ("invasion error : bad unit");
                $unit_infos = DB::table('units')->select('id', 'speed')->where('name', '=', $unit)->first();
                if ($unit_infos == null)
                    return ("invasion error : unknow unit");
                if ($min_speed == -1 || $unit_infos->speed < $min_speed)
                    $min_speed = $unit_infos->speed;
                if ($units_send == "")
                    $units_send .= $unit_infos->id . ":" . $quantity;
                else
                    $units_send .= ";" . $unit_infos->id . ":" . $quantity;
                $update_units_tab[$unit] = $city_units->$unit - $quantity;
            }
            $user_city = DB::table('cities')->select('x_pos', 'y_pos')->where('id', '=', $city_id)->first();
            $travel_duration = (abs($user_city->x_pos - $x_pos) + abs($user_city->y_pos - $y_pos)) * (3600 / $min_speed);
            $starting_point = $user_city->x_pos . "/" . $user_city->y_pos;
            $ending_point = $x_pos . "/" . $y_pos;
            DB::table('cities_units')->where('city_id', '=', $city_id)->update($update_units_tab);
            DB::table('traveling_units')->insert(
                ['city_id' => $city_id, 'owner' => $user_id, 'starting_point' => $starting_point, 'ending_point' => $ending_point, 'units' => $units_send, 'traveling_duration' => $travel_duration, 'finishing_date' => $travel_duration + time(), 'mission' => 5]
            );
            return "Good";
        }
    }

?>
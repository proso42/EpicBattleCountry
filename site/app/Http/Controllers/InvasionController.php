<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
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
            $city = DB::table('cities')
            ->where('owner', '=', $user_id)
            ->where('id', '=', $city_id)
            ->first();
            $food = $city->food;
            $compact_food = $food;
            $max_food = $city->max_food;
            $wood = $city->wood;
            $compact_wood = $wood;
            $max_wood = $city->max_wood;
            $rock = $city->rock;
            $compact_rock = $rock;
            $max_rock = $city->max_rock;
            $steel = $city->steel;
            $compact_steel = $steel;
            $max_steel = $city->max_steel;
            $gold = $city->gold;
            $compact_gold = $gold;
            $max_gold = $city->max_gold;
            if ($food > 999999)
                $compact_food = substr($food, 0, 5) . '...';
            if ($wood > 999999)
                $compact_wood = substr($wood, 0, 5) . '...';
            if ($rock > 999999)
                $compact_rock = substr($rock, 0, 5) . '...';
            if ($steel > 999999)
                $compact_steel = substr($steel, 0, 5) . '...';
            if ($gold > 999999)
                $compact_gold = substr($gold, 0, 5) . '...';
            $city_unit = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $units = DB::table('units')->select('name', 'storage')->get();
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
            $info_item = [];
            foreach ($city_items as $item => $quantity)
            {
                if ($quantity <= 0)
                    continue ;
                $info_item[$item] = ["ref" => $item, "name" => trans('item.' . $item), "quantity" => $quantity];
            }
            $user_cities = DB::table('cities')->select('name')->where('owner', '=', $user_id)->where('id', '!=', $city_id)->get();
            $res = ["food" => $food, "wood" => $wood, "rock" => $rock, "steel" => $steel, "gold" => $gold];
            return view('invasion', compact('is_admin', 'res', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold', 'info_unit', 'info_item', 'user_cities'));
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

        private function sec_to_date($duration)
        {
            $new_duration = "";
            if ($duration < 60)
                return ($duration . " s");
            if ($duration % 60 > 0)
                $new_duration = ($duration % 60) . " s";
            $duration = floor($duration / 60);
            if ($duration < 60)
                return ($duration . " m " . $new_duration);
            if ($duration % 60 > 0)
                $new_duration = ($duration % 60) . " m " . $new_duration;
            $duration = floor($duration / 60);
            if ($duration < 60)
                return ($duration . " h " . $new_duration);
            if ($duration % 60 > 0)
                $new_duration = ($duration % 60) . " h " . $new_duration;
            $duration = floor($duration / 24);
            if ($new_duration !== "")
                return ($duration . " j " . $new_duration);
            else
                return ($duration . " j");
        }

        public function calculate_move_units(Request $request)
        {
            $units = $request['units'];
            $units = explode(",", preg_replace('/[{}\"]/', '', $units));
            $tab = [];
            foreach ($units as $key)
            {
                $ex = explode(":", $key);
                $tab[$ex[0]] = $ex[1];
            }
            $res = $request['res'];
            $res = explode(",", preg_replace('/[{}\"]/', '', $res));
            $tab_res = [];
            foreach ($res as $key)
            {
                $ex = explode(":", $key);
                //$tab_res[$ex[0]] = $ex[1];
            }
            return ("ok");
            /*$city_target = $request['city_target'];
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
            foreach ($tab_res as $res => $val)
            {
                if ($val <= 0)
                    continue ;
                if (!isset($current_city->$res) || $current_city->$res < $val)
                    return ("invasion error : bad res or item");
                else
                    $fret += $val;
            }
            foreach ($tab as $unit => $quantity)
            {
                if ($quantity <= 0)
                    continue;
                if (!isset($city_units->$unit) || $city_units->$unit < $quantity)
                    return ("invasion error : bad unit");
                $unit_name_format = preg_replace('/_/', " ", $unit);
                $unit_infos = DB::table('units')->select('speed', 'storage')->where('name', '=', $unit_name_format)->first();
                if ($min_speed == -1 || $unit_infos->speed < $min_speed)
                    $min_speed = $unit_infos->speed;
                $storage += $unit_infos->storage;
            }
            if ($fret > $storage)
                return ("invasion error : fret > storage");
            $city_coord = DB::table('cities')->select('x_pos', 'y_pos')->where('id', '=', $city_id)->first();
            $travel_duration = $this->sec_to_date(((abs($city_coord->x_pos - $city_target_info->x_pos) + abs($city_coord->y_pos - $city_target_info->y_pos)) * (3600 / $min_speed)));
            return (trans('invasion.travel_duration') . " " . $travel_duration . " ");*/
        }

        public function move_units(Request $request)
        {
            $units = $request['units'];
            $units = explode(",", preg_replace('/[{}\"]/', '', $units));
            $tab = [];
            foreach ($units as $key)
            {
                $ex = explode(":", $key);
                $tab[$ex[0]] = $ex[1];
            }
            $city_target = $request['city_target'];
            $user_id = session()->get('user_id');
            $city_id = session()->get('city_id');
            $city_target_info = DB::table('cities')->where('name', '=', $city_target)->where('owner', '=', $user_id)->first();
            if ($city_target_info == null || $city_target_info->id == $city_id)
                return ("invasion error : bad city");
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
                $unit_name_format = preg_replace('/_/', " ", $unit);
                $unit_infos = DB::table('units')->select('id', 'speed')->where('name', '=', $unit_name_format)->first();
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
            $city_coord = DB::table('cities')->select('x_pos', 'y_pos')->where('id', '=', $city_id)->first();
            $travel_duration = ((abs($city_coord->x_pos - $city_target_info->x_pos) + abs($city_coord->y_pos - $city_target_info->y_pos)) * (3600 / $min_speed));
            if ($travel_duration < 0)
                return ("invasion error : bad travel");
            $finishing_date = $travel_duration + time();
            $starting_point = $city_coord->x_pos . "/" . $city_coord->y_pos;
            $ending_point = $city_target_info->x_pos . "/" . $city_target_info->y_pos;
            DB::table('traveling_units')->insert(["city_id" => $city_id, "owner" => $user_id, "starting_point" => $starting_point, "ending_point" => $ending_point, "units" => $units_send, "traveling_duration" => $travel_duration, "finishing_date" => $finishing_date, "mission" => 7]);
            DB::table('cities_units')->where('city_id', '=', $city_id)->update($update_units_tab);
            return ("good");
        }
    }

?>
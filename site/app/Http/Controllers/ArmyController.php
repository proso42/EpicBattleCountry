<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Cache;

    class ArmyController extends Controller
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
            $city_builds = DB::table('cities_buildings')
            ->where('owner', '=', $user_id)
            ->where('city_id', '=', $city_id)
            ->first();
            $city_techs = DB::table('cities_techs')->where('city_id', '=', $city_id)->first();
            $util = Common::get_utilities($user_id, $city_id);
            $allowed = 1;
            if ($city_builds->Caserne == 0)
            {
                $allowed = 0;
                return view('army', compact('is_admin', 'allowed', 'util'));
            }
            $busy = DB::table('waiting_units')
            ->where('city_id', '=', $city_id)
            ->first();
            if ($busy !== null)
            {
                $allowed = -1;
                $waiting_units = ["name" => trans('unit.' . preg_replace('/\s/', "_", (DB::table('units')->where('id', '=', $busy->unit_id)->value('name')))), "quantity" => $busy->quantity, "finishing_date" => $busy->finishing_date - time()];
                return view('army', compact('is_admin', 'allowed' , 'waiting_units', 'util'));
            }
            $allowed_units = $this->get_allowed_units($city_id, $user_race, $city_builds, $city_techs);
            return view('army', compact('is_admin', 'allowed', 'allowed_units','util'));
        }

        private function get_allowed_units($city_id, $user_race, $city_builds, $city_techs)
        {
            $all_units = DB::table('units')->get();
            $allowed_units = [];
            foreach ($all_units as $unit)
            {
                if ($unit->race_required != $user_race && $unit->race_required > 0)
                    continue;
                if ($unit->building_required !== "NONE")
                {
                    $building_required = explode(';', $unit->building_required);
                    $building_name = DB::table('army_buildings')->where('id', '=', $building_required[0])->value('name');
                    $building_name_format = preg_replace('/\s/', '_', $building_name);
                    $building_niv = $city_builds->$building_name_format;
                    if ($building_required[1] > $building_niv)
                        continue;
                }
                if ($unit->tech_required !== "NONE")
                {
                    $tech_name = DB::table('techs')->where('id', '=', $unit->tech_required)->value('name');
                    $tech_name_format = preg_replace('/\s/', '_', $tech_name);
                    $tech_niv = $city_techs->$tech_name_format;
                    if ($tech_niv <= 0)
                        continue ;
                }
                $res_required = explode(";", $unit->basic_price);
                $food_required = 0;
                $wood_required = 0;
                $rock_required = 0;
                $steel_required = 0;
                $gold_required = 0;
                foreach ($res_required as $res => $amount)
                {
                    if ($amount[-1] == "F")
                        $food_required = intval(substr($amount, 0, -1));
                    else if ($amount[-1] == "W")
                        $wood_required = intval(substr($amount, 0, -1));
                    else if ($amount[-1] == "R")
                        $rock_required = intval(substr($amount, 0, -1));
                    else if ($amount[-1] == "S")
                        $steel_required = intval(substr($amount, 0, -1));
                    else
                        $gold_required = intval(substr($amount, 0, -1));
                }
                if ($unit->item_needed !== "NONE")
                {
                    $items_required = [];
                    $items_needed = explode(";", $unit->item_needed);
                    foreach ($items_needed as $item => $val)
                    {
                        $item_name = DB::table('forge')->where('id', '=', $val)->value('name');
                        array_push($items_required, ["name" => trans('item.' . preg_replace('/\s/', '_', $item_name))]);
                    }
                }
                else
                    $items_required = null;
                if ($unit->mount > 0)
                    $mount = trans('mount.' . preg_replace('/\s/', "_", DB::table('mounts')->where('id', '=', $unit->mount)->value('mount_name')));
                else
                    $mount = null;
                $duration = $this->boost_unit($unit->duration, $city_id, $user_race, $unit->mount);
                $duration = Common::sec_to_date($duration);
                $this->boost_unit_stats($city_id, $unit);
                array_push($allowed_units, ["unit_id" => $unit->id, "name" => trans('unit.' . preg_replace('/\s/', "_", $unit->name)), "food" => $food_required,  "wood" => $wood_required, "rock" => $rock_required, "steel" => $steel_required, "gold" => $gold_required, "duration" => $duration, "items" => $items_required, "mount" => $mount, "life" => $unit->life, "speed" => $unit->speed, "power" => $unit->power, "storage" => $unit->storage]);
            }
            return $allowed_units;
        }

        private function boost_unit($duration, $city_id, $user_race, $mounted)
        {
            if ($mounted > 0)
            {
                if ($user_race == 1)
                    $build_boost = "Ecurie";
                else if ($user_race == 2)
                    $build_boost = "Likornerie";
                else if ($user_race == 3)
                    $build_boost = "Bergerie";
                else
                    $build_boost = "Loufterie";
            }
            else
                $build_boost = "Caserne";
            $build_boost = DB::table('cities_buildings')->where('city_id', '=', $city_id)->value($build_boost);
            if ($build_boost <= 0)
                return $duration;
            else
            {
                $duration = $this->apply_boost($build_boost, $duration);
                return ($duration > 0) ? $duration : 1;
            }
        }

        private function apply_boost($lvl, $init_val)
        {
            if ($lvl <= 0)
                return $init_val;
            for ($i = 0; $i < $lvl; $i++)
                $init_val *= 1.1;
            return round($init_val);
        }

        private function boost_unit_stats($city_id, $unit)
        {
            $all_techs = DB::table('techs')->get();
            $all_items = DB::table('forge')->get();
            $city_techs = DB::table('cities_techs')->where('city_id', '=', $city_id)->first();
            if ($unit->mount > 0)
                $unit->speed = $this->apply_boost($city_techs->Elevage, $unit->speed);
            if ($unit->item_needed == "NONE")
                return ;
            else
            {
                $split = explode(';', $unit->item_needed);
                foreach ($split as $item => $val)
                {
                    $tech_id = $all_items[$val - 1]->tech_required;
                    $type_boost = $all_techs[$tech_id - 1]->boost;
                    $tech_ref = preg_replace('/\s/', "_", $all_techs[$tech_id - 1]->name);
                    if ($type_boost == "life" || $type_boost == "power")
                        $unit->$type_boost = $this->apply_boost($city_techs->$tech_ref, $unit->$type_boost);
                }
            }
        }

        public function calculate_training_price(Request $request)
        {
            $quantity = $request['quantity'];
            $unit_id = $request['unit_id'];
            $city_id = session()->get('city_id');
            $user_id = session()->get('user_id');
            $user_race = DB::table('users')->where('id', '=', $user_id)->value('race');
            $unit = DB::table('units')
            ->where('id', '=', $unit_id)
            ->first();
            if ($unit === null)
                return ("army error : unknow unit");
            else if ($unit->race_required != $user_race && $unit->race_required > 0)
                return ("army error : bad user race for this unit");
            $city_res = DB::table('cities')
            ->where('id', '=', $city_id)
            ->first();
            $all_items = DB::table('forge')->select('name')->get();
            $duration = $this->boost_unit($unit->duration, $city_id, $user_race, $unit->mount);
            $duration = Common::sec_to_date($duration * $quantity);
            $food_required = 0;
            $enough_food = "fas fa-check icon-color-green";
            $wood_required = 0;
            $enough_wood = "fas fa-check icon-color-green";
            $rock_required = 0;
            $enough_rock = "fas fa-check icon-color-green";
            $gold_required = 0;
            $enough_steel = "fas fa-check icon-color-green";
            $steel_required = 0;
            $enough_gold = "fas fa-check icon-color-green";
            $enough_mount = "fas fa-check icon-color-green";
            $allowed = "OK";
            $res_required = explode(";", $unit->basic_price);
            if ($unit->mount == 0)
                $mount_required = 0;
            else
            {
                $mount_required = DB::table('mounts')->where('id', '=', $unit->mount)->value('mount_name');
                $mount_name_format = preg_replace('/\s/', "_", $mount_required);
                $mount_required = trans('mount.' . $mount_name_format);
                if ($city_res->$mount_name_format < $quantity)
                {
                    $enough_mount = "fas fa-times icon-color-red";
                    $allowed = "KO";
                }
            }
            $items_required = explode(";", $unit->item_needed);
            $items_owned = [];
            if ($unit->item_needed !== "NONE")
            {
                foreach ($items_required as $item => $val)
                {
                $item_name = $all_items[$val - 1]->name;
                $item_name_format = preg_replace('/\s/', "_", $item_name);
                if ($city_res->$item_name_format < $quantity)
                {
                    array_push($items_owned, ["item" => trans('item.' . $item_name_format), "icon" => "fas fa-times icon-color-red"]);
                    $allowed = "KO";
                }
                else
                    array_push($items_owned, ["item" => trans('item.' . $item_name_format), "icon" => "fas fa-check icon-color-green"]);
                }
            }            
            foreach ($res_required as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "W")
                    $wood_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "R")
                    $rock_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "S")
                    $steel_required = intval(substr($amount, 0, -1)) * $quantity;
                else
                    $gold_required = intval(substr($amount, 0, -1)) * $quantity;
            }
            if ($food_required > $city_res->food)
            {
                $enough_food = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            if ($wood_required > $city_res->wood)
            {
                $enough_wood = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            if ($rock_required > $city_res->rock)
            {
                $enough_rock = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            if ($steel_required > $city_res->steel)
            {
                $enough_steel = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            if ($gold_required > $city_res->gold)
            {
                $enough_gold = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            return ([$allowed, $food_required, $enough_food, $wood_required, $enough_wood, $rock_required, $enough_rock, $steel_required, $enough_steel, $gold_required, $enough_gold, $mount_required, $enough_mount, $duration, trans('unit.' . $unit->name), $items_owned]);
        }

        public function train_unit(Request $request)
        {
            $city_id = session()->get('city_id');
            $already_training = DB::table('waiting_units')->where('city_id', '=', $city_id)->value('id');
            $user_id = session()->get('user_id');
            $user_race = DB::table('users')->where('id', '=', $user_id)->value('race');
            $unit_id = $request['unit_id'];
            $quantity = $request['quantity'];
            $unit = DB::table('units')
            ->where('id', '=', $unit_id)
            ->first();
            if ($unit === null)
                return ("unit_error");
            else if ($already_training !== null)
                return ("hackeur !");
            else if ($unit->race_required != $user_race && $unit->race_required > 0)
                return ("army error : bad user race for this unit");
            $city_res = DB::table('cities')
            ->where('id', '=', $city_id)
            ->first();
            $duration = $this->boost_unit($unit->duration, $city_id, $user_race, $unit->mount);
            $finishing_date = ($duration * $quantity) + time();
            $food_required = 0;
            $wood_required = 0;
            $rock_required = 0;
            $gold_required = 0;
            $steel_required = 0;
            $res_required = explode(";", $unit->basic_price);
            foreach ($res_required as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "W")
                    $wood_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "R")
                    $rock_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "S")
                    $steel_required = intval(substr($amount, 0, -1)) * $quantity;
                else
                    $gold_required = intval(substr($amount, 0, -1)) * $quantity;
            }
            if ($food_required > $city_res->food || $wood_required > $city_res->wood || $rock_required > $city_res->rock || $steel_required > $city_res->steel || $gold_required > $city_res->gold)
                return ("not enough basic ressources");
            else
                $ressources_tab = ['food' => $city_res->food - $food_required, 'wood' => $city_res->wood - $wood_required, 'rock' => $city_res->rock - $rock_required, 'steel' => $city_res->steel - $steel_required, 'gold' => $city_res->gold - $gold_required];
            $mount_required = $unit->mount;
            if ($mount_required > 0)
            {
                $mount_name = preg_replace('/\s/', "_", DB::table('mounts')->where('id', '=', $mount_required)->value('mount_name'));
                if ($city_res->$mount_name < $quantity)
                    return ("not enough mount");
                else
                    $ressources_tab[$mount_name] = $city_res->$mount_name - $quantity;
            }
            if ($unit->item_needed !== "NONE")
            {
                $item_needed = explode(";", $unit->item_needed);
                $all_items = DB::table('forge')->select('name')->get();
                foreach ($item_needed as $item => $item_id)
                {
                    $item_name = $all_items[$item_id - 1]->name;
                    $item_name_format = preg_replace('/\s/', "_", $item_name);
                    if ($city_res->$item_name_format < $quantity)
                        return ("not enough items");
                    else
                    {
                        $ressources_tab[$item_name_format] = $city_res->$item_name_format - $quantity;
                    }
                }
            }
            DB::table('cities')
            ->where('id', '=', $city_id)
            ->update($ressources_tab);
            $id = DB::table('waiting_units')
            ->insertGetId(["city_id" => $city_id, "unit_id" => $unit->id, "finishing_date" => $finishing_date, "quantity" => $quantity]);
            return "Good";
        }
    }

?>
<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

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
            $city_builds = DB::table('cities_buildings')
            ->where('owner', '=', $user_id)
            ->where('city_id', '=', $city_id)
            ->first();
            $city_techs = DB::table('cities_techs')->where('city_id', '=', $city_id)->first();
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
            if ($city_builds->Caserne == 0)
            {
                $allowed = 0;
                return view('army', compact('allowed', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold'));
            }
            else
                $allowed = 1;
            $allowed_units = $this->get_allowed_units($city_id, $user_race, $city_builds, $city_techs);
            return view('army', compact('allowed', 'allowed_units','food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold'));
        }

        private function get_allowed_units($city_id, $user_race, $city_builds, $city_techs)
        {
            $all_units = DB::table('units')
            ->get();
            $allowed_units = [];
            foreach ($all_units as $unit)
            {
                if ($unit->race_required != $user_race)
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
                        $item_name = DB::table('forge')->where('id', '=', $val);
                        array_push($items_required, ["name" => $item_name]);
                    }
                }
                else
                    $items_required = null;
                if ($unit->mount > 0)
                    $mount = DB::table('mounts')->where('id', '=', $unit->mount)->value('mount_name');
                else
                    $mount = null;
                $duration = $this->sec_to_date($unit->duration);
                array_push($allowed_units, ["name" => $unit->name, "food" => $food_required,  "wood" => $wood_required, "rock" => $rock_required, "steel" => $steel_required, "gold" => $gold_required, "duration" => $duration, "items" => $items_required, "mount" => $mount, "life" => $unit->life, "speed" => $unit->speed, "power" => $unit->power]);
            }
            return $allowed_units;
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
    }

?>
<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class BuildingsController extends Controller
    {
        public function index()
        {
            if (!isset($_GET['activeTab'])|| ($_GET['activeTab'] !== "eco" && $_GET['activeTab'] !== "army" && $_GET['activeTab'] !== "defensive" && $_GET['activeTab'] !== "tech"))
                $first_active_tab = "eco";
            else
                $first_active_tab = $_GET['activeTab'];
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
            $allowed_eco_buildings = $this->get_allowed_buildings('eco_buildings');
            $allowed_army_buildings = $this->get_allowed_buildings('army_buildings');
            $allowed_defensive_buildings = $this->get_allowed_buildings('defensive_buildings');
            $allowed_tech_buildings = $this->get_allowed_buildings('tech_buildings');
            return view('buildings', compact('first_active_tab', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold', 'allowed_eco_buildings', 'allowed_army_buildings', 'allowed_defensive_buildings', 'allowed_tech_buildings'));
        }

        private function get_allowed_buildings($building_type)
        {
            $city_id = session()->get('city_id');
            $all_type_buildings = DB::table($building_type)
            ->get();
            $allowed_type_buildings = array();
            foreach ($all_type_buildings as $val)
            {
                $is_wip = DB::table('waiting_buildings')
                ->where('city_id', '=', $city_id)
                ->where('type', '=', $building_type)
                ->where('building_id', '=', $val->id)
                ->value('finishing_date');
                if ($is_wip == null)
                    $status = "OK";
                else
                    $status = "WIP";
                $niv = DB::table('cities')
                ->where('id', '=', $city_id)
                ->value($val->name);
                if ($niv >= 0)
                {
                    if ($niv == 0)
                    {
                        $allowed = 0;
                        if ($val->race_required !== "ALL")
                        {
                            $races_required = explode(";", $val->race_required);
                            foreach ($races_required as $race => $key)
                            {
                                if ($key == session()->get('user_race'))
                                {
                                    $allowed = 1;
                                    break; 
                                }
                            }
                        }
                        else
                            $allowed = 1;
                        if ($allowed == 0)
                            continue;
                        if ($val->building_required !== "NONE")
                        {
                            $buildings_required = explode(";", $val->building_required);
                            foreach ($buildings_required as $building => $key)
                            {
                                $building_name = DB::table($building_type)
                                ->where('id', '=', $key)
                                ->value('name');
                                $building_niv = DB::table('cities')
                                ->where('id', '=', $city_id)
                                ->value($building_name);
                                if ($building_niv <= 0)
                                {
                                    $allowed = 0;
                                    break;
                                }
                            }
                        }
                        if ($allowed == 0)
                            continue;
                    }
                    $res_required = explode(";", $val->basic_price);
                    $food_required = 0;
                    $wood_required = 0;
                    $rock_required = 0;
                    $steel_required = 0;
                    $gold_required = 0;
                    foreach ($res_required as $res => $amount)
                    {
                        if ($amount[-1] == "F")
                            $food_required = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "W")
                            $wood_required = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "R")
                            $rock_required = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "S")
                            $steel_required = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else
                            $gold_required = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                    }
                    $illustration = "images/" . $val->illustration . ".jpg";
                    if ($status === "OK")
                        $duration = $this->sec_to_date($niv, $val->duration, $val->levelup_price);
                    else
                        $duration = $is_wip - time();
                    array_push($allowed_type_buildings, ["status" => $status, "name" => $val->name, "niv" => $niv, "illustration" => $illustration, "duration" => $duration, "food_required" => $food_required, "wood_required" => $wood_required, "rock_required" => $rock_required, "steel_required" => $steel_required, "gold_required" => $gold_required]);
                }
                else
                    continue;
            }
            return $allowed_type_buildings;
        }

        private function get_exp_value($niv, $basic_value, $levelup)
        {
            $final_value = $basic_value;
            for ($i = 1; $i <= $niv; $i++)
                $final_value *= $levelup;
            return round($final_value, 0, PHP_ROUND_HALF_DOWN);
        }

        private function sec_to_date($niv, $duration, $levelup)
        {
            $new_duration = "";
            $duration = $this->get_exp_value($niv, $duration, $levelup);
            if ($duration < 60)
                return ($duration . " s");
            if ($duration % 60 > 0)
                $new_duration = ($duration % 60) . " s";
            $duration = round($duration / 60, 0, PHP_ROUND_HALF_DOWN);
            if ($duration < 60)
                return ($duration . " m " . $new_duration);
            if ($duration % 60 > 0)
                $new_duration = ($duration % 60) . " m " . $new_duration;
            $duration = round($duration / 60, 0, PHP_ROUND_HALF_DOWN);
            if ($duration < 60)
                return ($duration . " h " . $new_duration);
            if ($duration % 60 > 0)
                $new_duration = ($duration % 60) . " h " . $new_duration;
            $duration = round($duration / 24, 0, PHP_ROUND_HALF_DOWN);
            if ($new_duration !== "")
                return ($duration . " j " . $new_duration);
            else
                return ($duration . " j");
        }

        private function date_to_sec($date)
        {
            $date = preg_replace('/\s+/', "", $date);
            $finishing_date = time();
            if (strpos($date, "j") !== FALSE)
            {
                $tmp = explode("j", $date);
                $days = intval($tmp[0]);
                $date = $tmp[1];
                $finishing_date += ($days * 86400);
            }
            if (strpos($date, "h") !== FALSE)
            {
                $tmp = explode("h", $date);
                $hours = intval($tmp[0]);
                $date = $tmp[1];
                $finishing_date += ($hours * 3600);
            }
            if (strpos($date, "m") !== FALSE)
            {
                $tmp = explode("m", $date);
                $min = intval($tmp[0]);
                $date = $tmp[1];
                $finishing_date += ($min * 60);
            }
            if (strpos($date, "s") !== FALSE)
            {
                $tmp = explode("s", $date);
                $sec = intval($tmp[0]);
                $finishing_date += $sec;
            }
            return $finishing_date;
        }

        public function update(Request $request)
        {
            $city_id = session()->get('city_id');
            $next_level = $request['niv'] + 1;
            $building_name = $request['building_name'];
            $building_type = $request['building_type'];
            $building_id = DB::table($building_type)
            ->where('name', '=', $building_name)
            ->value('id');
            $food_required = $request['food_required'];
            $wood_required = $request['wood_required'];
            $rock_required = $request['rock_required'];
            $steel_required = $request['steel_required'];
            $gold_required = $request['gold_required'];
            $finishing_date = $this->date_to_sec($request['duration']);
            $city_res = DB::table('cities')
            ->select('food', 'wood', 'rock', 'steel', 'gold')
            ->where('id', '=', $city_id)
            ->first();
            if ($city_res->food < $food_required || $city_res->wood < $wood_required || $city_res->rock < $rock_required || $city_res->steel < $steel_required || $city_res->gold < $gold_required)
                return ;
            DB::table('cities')
            ->where('id', '=', $city_id)
            ->update(['food' => $city_res->food - $food_required, 'wood' => $city_res->wood - $wood_required, 'rock' => $city_res->rock - $rock_required, 'steel' => $city_res->steel - $steel_required, 'gold' => $city_res->gold - $gold_required]);
            $id = DB::table('waiting_buildings')
            ->insertGetId(["city_id" => $city_id, "type" => $building_type, "building_id" => $building_id, "finishing_date" => $finishing_date, "next_level" => $next_level]);
            $cmd = "cd /home/boss/www/scripts ; node ./finish_building.js " . $finishing_date  . " " . $id;
            exec($cmd);
        }
    }

?>
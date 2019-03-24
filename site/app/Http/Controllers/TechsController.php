<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class TechsController extends Controller
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
            $city_build = DB::table('cities_buildings')
            ->where('owner', '=', $user_id)
            ->where('city_id', '=', $city_id)
            ->first();
            if ($city_build->Laboratoire > 0)
                $allowed = 1;
            else
                $allowed = 0;
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
            $allowed_techs = $this->get_allowed_techs();
            return view('techs', compact('allowed', 'allowed_techs','food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold'));
        }

        private function get_allowed_techs()
        {
            $city_id = session()->get('city_id');
            $all_techs = DB::table('techs')
            ->get();
            $allowed_techs = array();
            foreach ($all_techs as $val)
            {
                $is_wip = DB::table('waiting_techs')
                ->where('city_id', '=', $city_id)
                ->where('tech_id', '=', $val->id)
                ->value('finishing_date');
                if ($is_wip == null)
                    $status = "OK";
                else
                    $status = "WIP";
                $niv = DB::table('cities_techs')
                ->where('city_id', '=', $city_id)
                ->value(preg_replace('/\s/',"_", $val->name));
                if ($niv >= 0)
                {
                    if ($niv == 0)
                    {
                        $allowed = 0;
                        if ($val->race_required !== "NONE")
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
                            for($i = 0; $i < count($buildings_required); $i += 2)
                            {
                                $building_type = $buildings_required[$i];
                                $building_id = $buildings_required[$i + 1];
                                $building_name = DB::table($building_type)
                                ->where('id', '=', $building_id)
                                ->value('name');
                                $building_niv = DB::table('cities_buildings')
                                ->where('id', '=', $city_id)
                                ->value(preg_replace('/\s/',"_", $building_name));
                                if ($building_niv == 0)
                                    $allowed = 0;
                            }
                        }
                        if ($allowed == 0)
                            continue;
                        if ($val->tech_required !== "NONE")
                        {
                            $techs_required = explode(";", $val->tech_required);
                            foreach ($techs_required as $tech => $key)
                            {
                                $tech_name = DB::table('techs')
                                ->where('id', '=', $key)
                                ->value('name');
                                $tech_name_format = preg_replace('/\s/', "_", $tech_name);
                                $tech_niv = DB::table('cities_techs')
                                ->where('id', '=', $city_id)
                                ->value($tech_name_format);
                                if ($tech_niv <= 0)
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
                    array_push($allowed_techs, ["status" => $status, "name" => $val->name, "niv" => $niv, "illustration" => $illustration, "duration" => $duration, "food_required" => $food_required, "wood_required" => $wood_required, "rock_required" => $rock_required, "steel_required" => $steel_required, "gold_required" => $gold_required]);
                }
                else
                    continue;
            }
            return $allowed_techs;
        }

        private function get_exp_value($niv, $basic_value, $levelup)
        {
            $final_value = intval($basic_value);
            for ($i = 1; $i <= $niv; $i++)
                $final_value *= $levelup;
            return floor($final_value);
        }

        private function sec_to_date($niv, $duration, $levelup)
        {
            $new_duration = "";
            $duration = $this->get_exp_value($niv, $duration, $levelup);
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
            $tech_name = $request['tech_name'];
            $tech_id = DB::table('techs')
            ->where('name', '=', $tech_name)
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
            $id = DB::table('waiting_techs')
            ->insertGetId(["city_id" => $city_id, "tech_id" => $tech_id, "finishing_date" => $finishing_date, "next_level" => $next_level]);
            $cmd = "cd /home/boss/www/scripts ; node ./finish_tech.js " . $finishing_date  . " " . $id;
            exec($cmd);
            return ;
        }
    }

?>
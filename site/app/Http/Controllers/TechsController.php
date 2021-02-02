<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
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
            $is_admin = DB::table('users')->where('id', '=', $user_id)->value("is_admin");
            $city_build = DB::table('cities_buildings')
            ->where('owner', '=', $user_id)
            ->where('city_id', '=', $city_id)
            ->first();
            if ($city_build->Laboratoire > 0)
                $allowed = 1;
            else
                $allowed = 0;
            $util = Common::get_utilities($user_id, $city_id);
            $allowed_techs = $this->get_allowed_techs();
            return view('techs', compact('is_admin', 'allowed', 'allowed_techs','util'));
        }

        public function get_description(Request $request)
        {
            $id = $request['id'];
            $tech_desc = DB::table('techs')->where('id', '=', $id)->value('description');
            if (!$tech_desc)
                return(["Result" => "Error", "Reason" => "Invalid Tech ID"]);
            return (["Result" => "Success", "description" => trans('tech.' . $building_desc)]);
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
                                ->where('city_id', '=', $city_id)
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
                                ->where('city_id', '=', $city_id)
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
                            $food_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "W")
                            $wood_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "R")
                            $rock_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "S")
                            $steel_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else
                            $gold_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                    }
                    $illustration = "images/" . $val->illustration . ".jpg";
                    if ($status === "OK")
                    {
                        $duration = $this->boost_lab($val->duration, $city_id);
                        $duration = Common::sec_to_date(Common::get_exp_value($niv, $duration, $val->levelup_price));
                    }
                    else
                        $duration = $is_wip - time();
                    array_push($allowed_techs, ["tech_id" => $val->id, "status" => $status, "name" => trans('tech.' . preg_replace('/\s/', "_", $val->name)), "niv" => $niv, "illustration" => $illustration, "duration" => $duration, "food_required" => $food_required, "wood_required" => $wood_required, "rock_required" => $rock_required, "steel_required" => $steel_required, "gold_required" => $gold_required]);
                }
                else
                    continue;
            }
            return $allowed_techs;
        }

        private function boost_lab($duration, $city_id)
        {
            $lab = DB::table('cities_buildings')->where('city_id', '=', $city_id)->value('Laboratoire');
            if ($lab <= 0)
                return $duration;
            else
            {
                for ($i = 0; $i < $lab; $i++)
                    $duration *= 0.9;
                return round($duration);
            }
        }

        private function get_unavailable_techs()
        {
            $city_id = session()->get('city_id');
            $city_res = DB::table('cities')->select('food', 'wood', 'rock', 'steel', 'gold')->where('id', '=', $city_id)->first();
            $all_techs = DB::table('techs')
            ->get();
            $forbidden_techs = array();
            foreach ($all_techs as $val)
            {
                $is_wip = DB::table('waiting_techs')
                ->where('city_id', '=', $city_id)
                ->where('tech_id', '=', $val->id)
                ->value('finishing_date');
                if ($is_wip != null)
                    continue;
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
                                ->where('city_id', '=', $city_id)
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
                                ->where('city_id', '=', $city_id)
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
                            $food_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "W")
                            $wood_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "R")
                            $rock_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else if ($amount[-1] == "S")
                            $steel_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                        else
                            $gold_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $val->levelup_price);
                    }
                    if ($food_required > $city_res->food || $wood_required > $city_res->wood || $rock_required > $city_res->rock || $steel_required > $city_res->steel || $gold_required > $city_res->gold)
                        array_push($forbidden_techs, ["tech_id" => "tech_" . $val->id, "food_required" => $food_required, "wood_required" => $wood_required, "rock_required" => $rock_required, "steel_required" => $steel_required, "gold_required" => $gold_required]);
                }
                else
                    continue;
            }
            return $forbidden_techs;
        }

        public function update(Request $request)
        {
            $city_id = session()->get('city_id');
            $user_id = session()->get('user_id');
            $user_race = session()->get('user_race');
            $tech_id = $request['tech_id'];
            $tech = DB::table('techs')
            ->where('id', '=', $tech_id)
            ->first();
            if ($tech === null)
                return ("tech error : unknow tech");
            $allowed = 0;
            if ($tech->race_required !== "NONE")
            {
                $races_allowed = explode(";", $tech->race_required);
                foreach ($races_allowed as $race)
                {
                    if ($race == $user_race)
                    {
                        $allowed = 1;
                        break ;
                    }
                }
                if ($allowed == 0)
                    return ("tech error : bad race");
            }
            if ($tech->building_required !== "NONE")
            {
                $split = explode(";", $tech->building_required);
                $building_type = $split[0];
                $building_id = $split[1];
                $building_name = preg_replace('/\s/', "_", DB::table($building_type)->where('id', '=', $building_id)->value('name'));
                $building_niv = DB::table('cities_buildings')->where('city_id', '=', $city_id)->value($building_name);
                if ($building_niv <= 0)
                    return ("tech error : missing building required");
            }
            if ($tech->tech_required !== "NONE")
            {
                $tech_name = preg_replace('/\s/', "_", DB::table('techs')->where('id', '=', $tech->tech_required)->value("name"));
                $tech_niv = DB::table('cities_techs')->where('city_id', '=', $city_id)->value($tech_name);
                if ($tech_niv <= 0)
                    return ("tech error : missing tech required");
            }
            $food_required = 0;
            $wood_required = 0;
            $rock_required = 0;
            $steel_required = 0;
            $gold_required = 0;
            $name = preg_replace('/\s/', "_", DB::table('techs')->where('id', '=', $tech_id)->value("name"));
            $niv = DB::table('cities_techs')->where('city_id', '=', $city_id)->value($name);
            $duration = $this->boost_lab($tech->duration, $city_id);
            $finishing_date = Common::get_exp_value($niv, $duration, $tech->levelup_price) + time();
            $res_required = explode(";", $tech->basic_price);
            foreach ($res_required as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $tech->levelup_price);
                else if ($amount[-1] == "W")
                    $wood_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $tech->levelup_price);
                else if ($amount[-1] == "R")
                    $rock_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $tech->levelup_price);
                else if ($amount[-1] == "S")
                    $steel_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $tech->levelup_price);
                else
                    $gold_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $tech->levelup_price);
            }
            $city_res = DB::table('cities')
            ->select('food', 'wood', 'rock', 'steel', 'gold')
            ->where('id', '=', $city_id)
            ->first();
            if ($city_res->food < $food_required || $city_res->wood < $wood_required || $city_res->rock < $rock_required || $city_res->steel < $steel_required || $city_res->gold < $gold_required)
                return ("tech error : missing ressources required");
            DB::table('cities')
            ->where('id', '=', $city_id)
            ->update(['food' => $city_res->food - $food_required, 'wood' => $city_res->wood - $wood_required, 'rock' => $city_res->rock - $rock_required, 'steel' => $city_res->steel - $steel_required, 'gold' => $city_res->gold - $gold_required]);
            $id = DB::table('waiting_techs')
            ->insertGetId(["city_id" => $city_id, "tech_id" => $tech_id, "finishing_date" => $finishing_date, "next_level" => $niv + 1]);
            $infos = ["time_remaining" => $finishing_date - time(), "food" => $city_res->food - $food_required, 'wood' => $city_res->wood - $wood_required, 'rock' => $city_res->rock - $rock_required, 'steel' => $city_res->steel - $steel_required, 'gold' => $city_res->gold - $gold_required];
            $forbidden_techs = $this->get_unavailable_techs();
            $infos["forbidden_techs"] = $forbidden_techs;
            return ($infos);
        }
    }

?>
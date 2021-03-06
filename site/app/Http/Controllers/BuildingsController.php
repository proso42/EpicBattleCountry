<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class BuildingsController extends Controller
    {
        public function index()
        {
            if (session()->has('first_active_tab'))
                $first_active_tab = session()->get('first_active_tab');
            else
            {
                $first_active_tab = "eco";
                session()->put(["first_active_tab" => "eco"]);
            }
            if ($first_active_tab != "eco" && $first_active_tab != "army" && $first_active_tab != "religious" && $first_active_tab != "tech")
            {
                $first_active_tab = "eco";
                session(["first_active_tab" => "eco"]);
            }
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
            $waiting_building = DB::table('waiting_buildings')->where('city_id', '=', $city_id)->first();
            if ($waiting_building)
                $waiting_building = 1;
            else
                $waiting_building = 0;
            $allowed_eco_buildings = $this->get_allowed_buildings('eco_buildings');
            $allowed_army_buildings = $this->get_allowed_buildings('army_buildings');
            $allowed_religious_buildings = $this->get_allowed_buildings('religious_buildings');
            $allowed_tech_buildings = $this->get_allowed_buildings('tech_buildings');
            return view('buildings', compact('is_admin', 'first_active_tab', 'util', 'waiting_building', 'allowed_eco_buildings', 'allowed_army_buildings', 'allowed_religious_buildings', 'allowed_tech_buildings'));
        }

        public function set_active_tab(Request $request)
        {
            $tab = $request['active_tab'];
            if ($tab != "eco" && $tab != "army" && $tab != "religious" && $tab != "tech")
                $first_active_tab = "eco";
            if (session()->has("first_active_tab"))
                session(["first_active_tab" => $tab]);
            else
                session()->put(["first_active_tab" => $tab]);
            return ("tab saved");
        }

        public function get_description(Request $request)
        {
            $id = $request['id'];
            $type = $request['type'];
            if ($type == 1)
                $type = "eco_buildings";
            else if ($type == 2)
                $type = "army_buildings";
            else if ($type == 3)
                $type = "religious_buildings";
            else if ($type == 4)
                $type = "tech_buildings";
            else
                return (["Result" => "Error", "Reason" => "Invalid Building Type"]);
            $building_desc = DB::table($type)->where('id', '=', $id)->value('description');
            if (!$building_desc)
                return(["Result" => "Error", "Reason" => "Invalid Building ID"]);
            return (["Result" => "Success", "description" => trans('building.' . $building_desc)]);
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
                $niv = DB::table('cities_buildings')
                ->where('city_id', '=', $city_id)
                ->value(preg_replace('/\s/',"_", $val->name));
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
                                $building_name_format = preg_replace('/\s/', "_", $building_name);
                                $building_niv = DB::table('cities_buildings')
                                ->where('city_id', '=', $city_id)
                                ->value($building_name_format);
                                if ($building_niv <= 0)
                                {
                                    $allowed = 0;
                                    break;
                                }
                            }
                        }
                        if ($allowed == 0)
                            continue;
                        if ($val->tech_required !== "NONE")
                        {
                            $tech_name = preg_replace('/\s/', "_", DB::table('techs')->where('id', '=', $val->tech_required)->value("name"));
                            $tech_niv = DB::table('cities_techs')->where('city_id', '=', $city_id)->value($tech_name);
                            if ($tech_niv <= 0)
                                continue ;
                        }
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
                        $duration = $this->boost_meca($val->duration, $city_id);
                        $duration = Common::sec_to_date(Common::get_exp_value($niv, $duration, $val->levelup_price));
                        
                    }
                    else
                        $duration = $is_wip - time();
                    array_push($allowed_type_buildings, ["status" => $status, "id" => $val->id, "name" => trans('building.' . preg_replace('/\s/', '_', $val->name)), "niv" => $niv, "illustration" => $illustration, "duration" => $duration, "food_required" => $food_required, "wood_required" => $wood_required, "rock_required" => $rock_required, "steel_required" => $steel_required, "gold_required" => $gold_required]);
                }
                else
                    continue;
            }
            return $allowed_type_buildings;
        }
    
        private function boost_meca($duration, $city_id)
        {
            $meca = DB::table('cities_techs')->where('city_id', '=', $city_id)->value('Meca');
            if ($meca <= 0)
                return $duration;
            else
            {
                for ($i = 0; $i < $meca; $i++)
                    $duration *= 0.9;
                return round($duration);
            }
        }

        public function update(Request $request)
        {
            $city_id = session()->get('city_id');
            $building_id = $request['id'];
            $building_type = preg_replace('/tab/', "buildings", $request['type']);
            if ($building_type !== "eco_buildings" && $building_type !== "army_buildings" && $building_type !== "religious_buildings" && $building_type !== "tech_buildings")
                return ("error : bad buildings type");
            $building_name = DB::table($building_type)->where('id', '=', $building_id)->value('name');
            if ($building_name === null)
                return ("error : bad id of building");
            $waiting_building = DB::table('waiting_buildings')->where('city_id', '=', $city_id)->first();
            if ($waiting_building)
                return (['Result' => 'Error', 'Reason' => 'Waiting for building']);
            $niv = DB::table('cities_buildings')->where('city_id', '=', $city_id)->value(preg_replace('/\s/', "_", $building_name));
            $alreday_waiting = DB::table('waiting_buildings')
            ->where('city_id', '=', $city_id)
            ->where('type', '=', $building_type)
            ->where('building_id', '=', $building_id)
            ->value('id');
            if ($alreday_waiting !== null && $alreday_waiting > 0)
                return ("error : already waiting");
            $building_info = DB::table($building_type)
            ->where('id', '=', $building_id)
            ->first();
            $allowed = 0;
            //return ("OK");
            if ($building_info->race_required !== "ALL")
            {
                $races_required = explode(";", $building_info->race_required);
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
                return ("error : bad race");
            if ($building_info->building_required !== "NONE")
            {
                $buildings_required = explode(";", $building_info->building_required);
                foreach ($buildings_required as $building => $key)
                {
                    $building_required_name = DB::table($building_type)
                    ->where('id', '=', $key)
                    ->value('name');
                    $building_required_name_format = preg_replace('/\s/', "_", $building_required_name);
                    $building_required_niv = DB::table('cities_buildings')
                    ->where('city_id', '=', $city_id)
                    ->value($building_required_name_format);
                    if ($building_required_niv <= 0)
                    {
                        $allowed = 0;
                        break;
                    }
                }
            }
            if ($allowed == 0)
                return ("error : bad building required");
            $duration = $this->boost_meca($building_info->duration, $city_id);
            $finishing_date = Common::get_exp_value($niv, $duration, $building_info->levelup_price) + time();
            $res_required = explode(";", $building_info->basic_price);
            $food_required = 0;
            $wood_required = 0;
            $rock_required = 0;
            $steel_required = 0;
            $gold_required = 0;
            foreach ($res_required as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $building_info->levelup_price);
                else if ($amount[-1] == "W")
                    $wood_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $building_info->levelup_price);
                else if ($amount[-1] == "R")
                    $rock_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $building_info->levelup_price);
                else if ($amount[-1] == "S")
                    $steel_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $building_info->levelup_price);
                else
                    $gold_required = Common::get_exp_value($niv, intval(substr($amount, 0, -1)), $building_info->levelup_price);
            }
            $city_res = DB::table('cities')
            ->select('food', 'wood', 'rock', 'steel', 'gold')
            ->where('id', '=', $city_id)
            ->first();
            if ($city_res->food < $food_required || $city_res->wood < $wood_required || $city_res->rock < $rock_required || $city_res->steel < $steel_required || $city_res->gold < $gold_required)
                return ("error : need more ressources");
            DB::table('cities')
            ->where('id', '=', $city_id)
            ->update(['food' => $city_res->food - $food_required, 'wood' => $city_res->wood - $wood_required, 'rock' => $city_res->rock - $rock_required, 'steel' => $city_res->steel - $steel_required, 'gold' => $city_res->gold - $gold_required]);
            $id = DB::table('waiting_buildings')
            ->insertGetId(["city_id" => $city_id, "type" => $building_type, "building_id" => $building_id, "finishing_date" => $finishing_date, "next_level" => $niv + 1]);
            $infos = ["trad" => trans('common.unavailable'), "time_remaining" => $finishing_date - time(), "food" => Common::compact_nb($city_res->food - $food_required), 'wood' => Common::compact_nb($city_res->wood - $wood_required), 'rock' => Common::compact_nb($city_res->rock - $rock_required), 'steel' => Common::compact_nb($city_res->steel - $steel_required), 'gold' => Common::compact_nb($city_res->gold - $gold_required)];
            return ($infos);
        }
    }

?>
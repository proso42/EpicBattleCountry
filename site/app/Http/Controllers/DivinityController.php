<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class DivinityController extends Controller
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
            if (session()->has('divinity_active_tab'))
                $divinity_active_tab = session()->get('divinity_active_tab');
            else
            {
                $divinity_active_tab = "blessing";
                session()->put(["divinity_active_tab" => "blessing"]);
            }
            if ($divinity_active_tab != "blessing" && $divinity_active_tab != "disaster")
            {
                $divinity_active_tab = "blessing";
                session(["divinity_active_tab" => "blessing"]);
            }
            $util = Common::get_utilities($user_id, $city_id);
            $disaster_cool_down = DB::table('magic_cool_down')->where('city_id', '=', $city_id)->where('type', '=', 'disaster')->value('finishing_date');
            if ($disaster_cool_down == null)
            {
                $allowed_disaster = $this->get_allowed_disaster($city_id, $user_race, $util->faith);
                $visible_cities = Common::get_targetable_cities($city_id, $user_id, $util->x_pos, $util->y_pos);
            }
            else
            {
                $allowed_disaster = null;
                $visible_cities = null;
                $disaster_cool_down = Common::sec_to_date($disaster_cool_down);
            }
            return view('divinity', compact('is_admin', 'util', 'divinity_active_tab', 'allowed_disaster', 'disaster_cool_down', 'visible_cities'));
        }

        public function set_active_divinity(Request $request)
        {
            $tab = $request['active_tab'];
            if ($tab != "blessing" && $tab != "disaster")
                $divinity_active_tab = "blessing";
            if (session()->has("divinity_active_tab"))
                session(["divinity_active_tab" => $tab]);
            else
                session()->put(["divinity_active_tab" => $tab]);
            return ("tab saved");
        }

        private function disaster_is_allowed($city_id, $disaster, $user_race, $all_reg_building, $user_buildings, $faith)
        {
            // this part check if the user race allow him to use this disaster
            if ($disaster->race_required != "NONE")
            {
                if (strstr($disaster->race_required, ";"))
                {
                    $split = explode(";", $disaster->race_required);
                    $ko = true;
                    foreach ($split as $race => $val)
                    {
                        if ($val == $user_race)
                        {
                            $ko = false;
                            break ;
                        }
                    }
                    if ($ko == true)
                        return false;
                }
                else
                {
                    if ($user_race != $disaster->race_required)
                        return false;
                }
            }
            // this part check if the player has all required buildings to use this disaster
            if ($disaster->building_required != "NONE")
            {
                $split_semicolon = explode(";", $disaster->building_required);
                foreach ($split_semicolon as $elem)
                {
                    $split_slash = explode("/", $elem);
                    $ko_slash = true;
                    foreach ($split_slash as $sub_elem)
                    {
                        $split_two_points = explode(":", $sub_elem);
                        $building_lvl = $split_two_points[1];
                        $building_name = $all_reg_building[$split_two_points[0] - 1]->name;
                        if ($user_buildings[$building_name] >= $building_lvl)
                        {
                            $ko_slash = false;
                            break;
                        }
                    }
                    if ($ko_slash == true)
                        return false;
                }
            }
            return true;
        }

        private function get_allowed_disaster($city_id, $user_race, $faith)
        {
            $all_disaster = DB::table('disasters')->get();
            $all_reg_building = DB::table('religious_buildings')->get();
            $ret = DB::table('cities_buildings')->where('city_id', '=', $city_id)->first();
            $user_buildings = [];
            $allowed_disaster = [];
            foreach ($ret as $building => $val)
                $user_buildings[preg_replace('/_/', " ", $building)] = $val;
            foreach ($all_disaster as $disaster)
            {
                if ($this->disaster_is_allowed($city_id, $disaster, $user_race, $all_reg_building, $user_buildings, $faith) == false)
                    continue;
                else
                {
                    array_push($allowed_disaster, [
                        "name" => trans('divinity.disaster_' . $disaster->name),
                        "illustration" => "images/" . $disaster->illustration . ".jpg",
                        "faith_cost" => $disaster->faith_cost,
                        "cool_down" => Common::sec_to_date($disaster->cool_down),
                        "desc" => trans('divinity.disaster_desc_' . $disaster->name),
                        "id" => $disaster->id
                    ]);
                }
            }
            return $allowed_disaster;
        }
    }

?>
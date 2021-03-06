<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class ExplorationController extends Controller
    {
        public function index(Request $request)
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
            if ($user_race == 1)
                $explo_unit_name = "Explorateur";
            else if ($user_race == 2)
                $explo_unit_name = "Ranger";
            else if ($user_race == 3)
                $explo_unit_name = "Fouineur";
            else
                $explo_unit_name = "Eclaireur";
            $allowed = DB::table('cities_techs')->where('city_id', '=', $city_id)->value('Exploration');
            if ($allowed <= 0)
                return view('exploration', compact('allowed', 'is_admin', 'util'));
            $waiting_scouting = DB::table('traveling_units')->where('city_id', '=', $city_id)->where('mission', '>=', 1)->where('mission', '<=', 4)->count();
            $explo = [];
            $unit_avaible = DB::table('cities_units')->where('city_id', '=', $city_id)->value($explo_unit_name);
            $explo_unit_name = trans('unit.' . $explo_unit_name);
            $explo[0] = ["unit_required" => 1, "food_required" => 100, "wood_required" => 0, "rock_required" => 0, "steel_required" => 0, "gold_required" => 0, 'illustration' => "images/explo_scouting.jpg"]; //Recconnaisance
            $explo[1] = ["unit_required" => 1, "food_required" => 100, "wood_required" => 0, "rock_required" => 0, "steel_required" => 0, "gold_required" => 0, 'illustration' => "images/explo_dungeon.jpg"]; //Donjon
            $explo[2] = ["unit_required" => 1, "food_required" => 100, "wood_required" => 0, "rock_required" => 0, "steel_required" => 0, "gold_required" => 0, 'illustration' => "images/explo_battlefield.jpg"]; //Champs de battaille
            $explo[3] = ["unit_required" => 5, "food_required" => 100, "wood_required" => 10000, "rock_required" => 5000, "steel_required" => 2500, "gold_required" => 1000, 'illustration' => "images/explo_colonize.jpg"]; //Nouvelle ville
            return view('exploration', compact('allowed', 'waiting_scouting', 'is_admin', 'util', 'explo_unit_name', 'unit_avaible', 'explo'));
        }

        public function time_explo(Request $request)
        {
            $dest_x = $request['dest_x'];
            $dest_y = $request['dest_y'];
            $choice = $request['choice'];
            if ($dest_x < -2000 || $dest_x > 2000 || $dest_y < -2000 || $dest_y > 2000 || $choice < 1 || $choice > 4 || !is_numeric($dest_x) || !is_numeric($dest_y) || !is_numeric($choice))
                return 1;
            $user_id = session()->get('user_id');
            $user_race = session()->get('user_race');
            $city_id = session()->get("city_id");
            if ($user_race === null)
            {
                $user_race = DB::table('users')
                ->where('id', '=', $user_id)
                ->value('race');
                session()->put(['user_race' => $user_race]);
            }
            if ($user_race == 1)
                $unit = "Explorateur";
            else if ($user_race == 2)
                $unit = "Ranger";
            else if ($user_race == 3)
                $unit = "Fouineur";
            else
                $unit = "Eclaireur";
            $unit_avaible = DB::table('cities_units')->where('city_id', '=', $city_id)->value($unit);
            $city_res = DB::table('cities')->select('food', 'wood', 'rock', 'steel', 'gold', 'x_pos', 'y_pos')->where('id', '=', $city_id)->first();
            if ($city_res->x_pos == $dest_x && $city_res->y_pos == $dest_y)
                return ("no_move");
            $unit_required = 1;
            $food_required = 100;
            $wood_required = 0;
            $rock_required = 0;
            $steel_required = 0;
            $gold_required = 0;
            if ($choice == 4)
            {
                $unit_required = 5;
                $wood_required = 10000;
                $rock_required = 5000;
                $steel_required = 2500;
                $gold_required = 1000;
            }
            if ($unit_avaible < $unit_required || $food_required > $city_res->food || $wood_required > $city_res->wood || $rock_required > $city_res->rock || $steel_required > $city_res->steel || $gold_required > $city_res->gold)
                return 1;
            $speed = 3600 / (DB::table('units')->where('name', '=', $unit)->value('speed'));
            $finishing_date = Common::sec_to_date((abs($city_res->x_pos - $dest_x) + abs($city_res->y_pos - $dest_y)) * $speed);
            $cartographe = DB::table('cities_buildings')->where('city_id', '=', $city_id)->value('Cartographe');
            $x_min = $city_res->x_pos - $cartographe;
            $x_max = $city_res->x_pos + $cartographe;
            $y_min = $city_res->y_pos - $cartographe;
            $y_max = $city_res->y_pos + $cartographe;
            if ($dest_x < $x_min || $dest_x > $x_max || $dest_y < $y_min || $dest_y > $y_max)
                return ($finishing_date . ";warning");
            else
                return ($finishing_date . ";easy");
        }

        public function send_expedition(Request $request)
        {
            $dest_x = $request['dest_x'];
            $dest_y = $request['dest_y'];
            $choice = $request['choice'];
            if ($dest_x < -2000 || $dest_x > 2000 || $dest_y < -2000 || $dest_y > 2000 || $choice < 1 || $choice > 4 || !is_numeric($dest_x) || !is_numeric($dest_y) || !is_numeric($choice))
                return ("error : bad coord");
            $user_id = session()->get('user_id');
            $user_race = session()->get('user_race');
            $city_id = session()->get("city_id");
            $tech_lvl = DB::table('cities_techs')->where('city_id', '=', $city_id)->value('Exploration');
            $nb_waiting_scouting = DB::table('traveling_units')->where('city_id', '=', $city_id)->where('mission', '>=', 1)->where('mission', '<=', 4)->count();
            if ($tech_lvl - $nb_waiting_scouting <= 0)
                return ("error : expedition unavailable");
            if ($user_race === null)
            {
                $user_race = DB::table('users')
                ->where('id', '=', $user_id)
                ->value('race');
                session()->put(['user_race' => $user_race]);
            }
            if ($user_race == 1)
                $unit = "Explorateur";
            else if ($user_race == 2)
                $unit = "Ranger";
            else if ($user_race == 3)
                $unit = "Fouineur";
            else
                $unit = "Eclaireur";
            $unit_id = DB::table('units')->where('name', '=', $unit)->value('id');
            $unit_avaible = DB::table('cities_units')->where('city_id', '=', $city_id)->value($unit);
            $city_res = DB::table('cities')->select('food', 'wood', 'rock', 'steel', 'gold', 'x_pos', 'y_pos')->where('id', '=', $city_id)->first();
            if ($city_res->x_pos == $dest_x && $city_res->y_pos == $dest_y)
                return ("error : no_move");
            $unit_required = 1;
            $food_required = 100;
            $wood_required = 0;
            $rock_required = 0;
            $steel_required = 0;
            $gold_required = 0;
            if ($choice == 4)
            {
                $unit_required = 5;
                $wood_required = 10000;
                $rock_required = 5000;
                $steel_required = 2500;
                $gold_required = 1000;
            }
            if ($unit_avaible < $unit_required || $food_required > $city_res->food || $wood_required > $city_res->wood || $rock_required > $city_res->rock || $steel_required > $city_res->steel || $gold_required > $city_res->gold)
                return ("error : not enough ressources or unit(s)");
            $speed = 3600 / (DB::table('units')->where('name', '=', $unit)->value('speed'));
            $finishing_date = ((abs($city_res->x_pos - $dest_x) + abs($city_res->y_pos - $dest_y)) * $speed) + time();
            DB::table('cities')->where('id', '=', $city_id)->update(["food" => $city_res->food - $food_required, "wood" => $city_res->wood - $wood_required, "rock" => $city_res->rock - $rock_required, "steel" => $city_res->steel - $steel_required, "gold" => $city_res->gold - $gold_required]);
            DB::table('cities_units')->where('city_id', '=', $city_id)->update([$unit => $unit_avaible - $unit_required]);
            $traveling_id = DB::table('traveling_units')->insertGetId([
                "city_id" => $city_id,
                "owner" => $user_id,
                "starting_point" => $city_res->x_pos . "/" . $city_res->y_pos,
                "ending_point" => $dest_x . "/" . $dest_y,
                "units" => $unit_id . ":" . $unit_required,
                "traveling_duration" => ((abs($city_res->x_pos - $dest_x) + abs($city_res->y_pos - $dest_y)) * $speed),
                "finishing_date" => $finishing_date,
                "mission" => $choice
            ]);
            $infos = ["tech_lvl" => $tech_lvl, "waiting_scouting" => $nb_waiting_scouting + 1, "food" => $city_res->food - $food_required, "wood" => $city_res->wood - $wood_required, "rock" => $city_res->rock - $rock_required, "steel" => $city_res->steel - $steel_required, "gold" => $city_res->gold - $gold_required, "unit_available" => $unit_avaible - $unit_required];
            return ($infos);
        }
    }
?>
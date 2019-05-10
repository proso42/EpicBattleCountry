<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class MapController extends Controller
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
            $user_race_name = DB::table('races')->where('id', '=', $user_race)->value("race_name");
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
            $util = Common::get_utilities($user_id, $city_id);
            $cartographer = DB::table('cities_buildings')
            ->where('city_id', '=', $city_id)
            ->value('Cartographe');
            if ($cartographer == 0)
                return view('map', compact('is_admin', 'cartographer', 'util'));
            if (isset($_GET['x_offset']) && $cartographer > 11 && abs($_GET['x_offset']) <= $cartographer - 11)
                $x_pos = $util->x_pos + $_GET['x_offset'];
            else
                $x_pos = $util->x_pos;
            if (isset($_GET['y_offset']) && $cartographer > 11 && abs($_GET['y_offset']) <= $cartographer - 11)
                $y_pos = $util->y_pos + $_GET['y_offset'];
            else
                $y_pos = $util->y_pos;
            if ($cartographer > 11)
            {
                $move_map = 1;
                $cartographer = 11;
            }
            else
                $move_map = 0;
            $all_cells = Common::get_visible_cells($x_pos, $y_pos, $cartographer);
            $visible_cells = [];
            $capital = DB::table('cities')->select('x_pos', 'y_pos')->where('owner', '=', $user_id)->where('is_capital', '=', 1)->first();
            foreach ($all_cells as $cell)
            {
                if ($cell->x_pos == $capital->x_pos && $cell->y_pos == $capital->y_pos)
                    array_push($visible_cells, ["type" => trans('map.capital'), "format_type" => "capital", "x_pos" => $cell->x_pos, "y_pos" => $cell->y_pos, "background-color" => "lemonchiffon", "color" => "green", "class" => "fa-star", "name" =>  $util->name, "diplomatie" => trans('map.owned'), "race" => trans('common.' . $user_race_name)]);
                else if ($cell->type == "water")
                    array_push($visible_cells, ["type" => trans('map.water'), "format_type" => "water", "x_pos" => $cell->x_pos, "y_pos" => $cell->y_pos, "color" => "black", "background-color" => "steelblue", "class" => $cell->icon]);
                else if ($cell->type == "city")
                {
                    $city_info = DB::table('cities')
                    ->select("name", 'owner')
                    ->where('x_pos', '=', $cell->x_pos)
                    ->where('y_pos', '=', $cell->y_pos)
                    ->first();
                    if ($city_info->owner == $user_id)
                    {
                        $color = "green";
                        $diplomatie = trans('map.owned');
                        $city_race_name = trans('common.' . $user_race_name);
                    }
                    else
                    {
                        $color = "black";
                        $diplomatie = trans('map.other');
                        $city_race = DB::table('users')->where('id', '=', $city_info->owner)->value('race');
                        $city_race_name = trans('common.' . DB::table('races')->where('id', '=', $city_race)->value("race_name"));
                    }
                    array_push($visible_cells, ["type" => trans('map.city'), "format_type" => "city", "x_pos" => $cell->x_pos, "y_pos" => $cell->y_pos, "background-color" => "lemonchiffon", "color" => $color, "class" => $cell->icon, "name" => $city_info->name, "diplomatie" => $diplomatie, "race" => $city_race_name]);
                }
                else
                    array_push($visible_cells, ["type" => trans('map.' . $cell->type), "format_type" => $cell->type, "x_pos" => $cell->x_pos, "y_pos" => $cell->y_pos, "color" => "black", "background-color" => "lemonchiffon", "class" => $cell->icon]); 

            }
            return view('map', compact('is_admin', 'move_map' ,'cartographer', 'visible_cells', 'x_pos', 'y_pos', 'util'));
        }
    }

?>
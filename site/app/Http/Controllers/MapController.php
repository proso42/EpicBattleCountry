<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
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
            $cartographer = DB::table('cities_buildings')
            ->where('city_id', '=', $city_id)
            ->value('Cartographe');
            if ($cartographer == 0)
                return view('map', compact('cartographer', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold'));
            $all_cells = DB::table('map')
            ->where('x_pos' ,'>=', $city->x_pos - $cartographer)
            ->where('x_pos', '<=', $city->x_pos + $cartographer)
            ->where('y_pos', '>=', $city->y_pos - $cartographer)
            ->where('y_pos', '<=', $city->y_pos + $cartographer)
            ->orderBy('x_pos', 'asc', 'y_pos', 'desc')
            ->get();
            $visible_cells = [];
            $x_pos = $city->x_pos;
            $y_pos = $city->y_pos;
            foreach ($all_cells as $cell)
            {
                if ($cell->x_pos == $x_pos && $cell->y_pos == $y_pos)
                    array_push($visible_cells, ["background-color" => "lemonchiffon", "class" => "fa-star"]);
                else if ($cell->type == "water")
                    array_push($visible_cells, ["background-color" => "steelblue", "class" => $cell->icon]);
                else
                    array_push($visible_cells, ["background-color" => "lemonchiffon", "class" => $cell->icon]); 

            }
            return view('map', compact('cartographer', 'visible_cells', 'x_pos', 'y_pos', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold'));
        }
    }

?>
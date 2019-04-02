<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
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
            $city = DB::table('cities')
            ->where('owner', '=', $user_id)
            ->where('id', '=', $city_id)
            ->first();
            $city_name = $city->name;
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
            if ($user_race == 1)
                $explo_unit_name = "Explorateur";
            else if ($user_race == 2)
                $explo_unit_name = "Ranger";
            else if ($user_race == 3)
                $explo_unit_name = "Fouineur";
            else
                $explo_unit_name = "Eclaireur";
            $explo = [];
            $unit_avaible = DB::table('cities_units')->where('city_id', '=', $city_id)->value($explo_unit_name);
            $explo[0] = ["unit_required" => 1, "food_required" => 100, "wood_required" => 0, "rock_required" => 0, "steel_required" => 0, "gold_required" => 0, 'illustration' => "images/explo_scouting.jpg"]; //Recconnaisance
            $explo[1] = ["unit_required" => 1, "food_required" => 100, "wood_required" => 0, "rock_required" => 0, "steel_required" => 0, "gold_required" => 0, 'illustration' => "images/explo_dungeon.jpg"]; //Donjon
            $explo[2] = ["unit_required" => 1, "food_required" => 100, "wood_required" => 0, "rock_required" => 0, "steel_required" => 0, "gold_required" => 0, 'illustration' => "images/explo_battlefield.jpg"]; //Champs de battaille
            $explo[3] = ["unit_required" => 5, "food_required" => 100, "wood_required" => 10000, "rock_required" => 5000, "steel_required" => 2500, "gold_required" => 1000, 'illustration' => "images/explo_colonize.jpg"]; //Nouvelle ville
            return view('exploration', compact('food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold', 'explo_unit_name', 'unit_avaible', 'explo'));
        }  
    }
?>
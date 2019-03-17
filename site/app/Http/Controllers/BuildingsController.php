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
            $all_eco_buildings = DB::table('eco_buildings')
            ->get();
            $allowed_eco_buildings = array();
            foreach ($all_eco_buildings as $val)
            {
                echo ("foreach;");
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
                                if ($key == $user_race)
                                {
                                    $allowed = 1;
                                    break; 
                                }
                            }
                        }
                        if ($allowed == 0)
                            continue;
                        if ($val->building_required !== "NONE")
                        {
                            $buildings_required = explode(";", $val->building_required);
                            foreach ($buildings_required as $building => $key)
                            {
                                $building_name = DB::table('eco_buildings')
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
                    $illustration = "images/" . $val->illustration . ".jpg";
                    array_push($allowed_eco_buildings, ["name" => $val->name, "niv" => $niv, "illustration" => $illustration, "food_required" => $food_required, "wood_required" => $wood_required, "rock_required" => $rock_required, "steel_required" => $steel_required, "gold_required" => $gold_required]);
                }
                else
                    continue;
            }
            dd($allowed_eco_buildings);
            return view('buildings', compact('food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold', 'allowed_eco_buildings'));
        }
    }

?>
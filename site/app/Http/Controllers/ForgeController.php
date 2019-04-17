<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class ForgeController extends Controller
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
            $allowed = DB::table('cities_buildings')
            ->where('city_id', '=', $city_id)
            ->value('Forge');
            if ($allowed == 0)
                return view('forge', compact('is_admin', 'allowed' ,'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold'));
            $busy = DB::table('waiting_items')
            ->where('city_id', '=', $city_id)
            ->first();
            if ($busy !== null)
            {
                $allowed = -1;
                $waiting_item = ["name" => trans('item.' . preg_replace('/\s/', "_", DB::table('forge')->where('id', '=', $busy->item_id)->value('name'))), "quantity" => $busy->quantity, "finishing_date" => $busy->finishing_date - time()];
                return view('forge', compact('is_admin', 'allowed' , 'waiting_item', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold'));
            }
            $allowed_items = $this->get_allowed_items($city_id, $user_race);
            return view('forge', compact('is_admin', 'allowed' ,'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold', 'allowed_items'));
        }

        private function get_allowed_items($city_id, $user_race)
        {
            $allowed_items = array();
            $city_buildings = DB::table('cities_buildings')
            ->where('city_id', '=', $city_id)
            ->first();
            $city_techs = DB::table('cities_techs')
            ->where('city_id', '=', $city_id)
            ->first();
            $all_items = DB::table('forge')
            ->get();
            foreach ($all_items as $item)
            {
                $allowed = 1;
                if ($item->race_required !== "NONE")
                {
                    $races_allowed = explode(";", $item->race_required);
                    foreach ($races_allowed as $race)
                    {
                        if ($race == $user_race)
                        {
                            $allowed = 2;
                            break;
                        }
                    }
                    if ($allowed == 1)
                        continue;
                }
                if ($item->building_required !== "NONE")
                {
                    $buildings_required = explode(";", $item->building_required);
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
                        {
                            $allowed = 0;
                            break;
                        }
                    }
                    if ($allowed == 0)
                        continue;
                }
                if ($item->tech_required !== "NONE")
                {
                    $techs_required = explode(";", $item->tech_required);
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
                    if ($allowed == 0)
                        continue;
                }
                $res_required = explode(";", $item->price);
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
                $duration = $this->sec_to_date($item->duration);
                array_push($allowed_items, ["name" => trans('item.' . preg_replace('/\s/', "_", $item->name)), "item_id" => $item->id, "food_required" => $food_required, "wood_required" => $wood_required, "rock_required" => $rock_required, "steel_required" => $steel_required, "gold_required" => $gold_required, "duration" => $duration]); 
            }
            return $allowed_items;
        }  

        private function sec_to_date($duration)
        {
            $new_duration = "";
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

        public function calculate_price(Request $request)
        {
            $item_id = $request['item_id'];
            $quantity = $request['quantity'];
            $city_id = session()->get('city_id');
            $item = DB::table('forge')
            ->where('id', '=', $item_id)
            ->first();
            if ($item === null)
                return ("item_error");
            $city_res = DB::table('cities')
            ->where('id', '=', $city_id)
            ->first();
            $duration = $this->sec_to_date($item->duration * $quantity);
            $food_required = 0;
            $enough_food = "fas fa-check icon-color-green";
            $wood_required = 0;
            $enough_wood = "fas fa-check icon-color-green";
            $rock_required = 0;
            $enough_rock = "fas fa-check icon-color-green";
            $gold_required = 0;
            $enough_steel = "fas fa-check icon-color-green";
            $steel_required = 0;
            $enough_gold = "fas fa-check icon-color-green";
            $allowed = "OK";
            $res_required = explode(";", $item->price);
            foreach ($res_required as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "W")
                    $wood_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "R")
                    $rock_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "S")
                    $steel_required = intval(substr($amount, 0, -1)) * $quantity;
                else
                    $gold_required = intval(substr($amount, 0, -1)) * $quantity;
            }
            if ($food_required > $city_res->food)
            {
                $enough_food = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            if ($wood_required > $city_res->wood)
            {
                $enough_wood = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            if ($rock_required > $city_res->rock)
            {
                $enough_rock = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            if ($steel_required > $city_res->steel)
            {
                $enough_steel = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            if ($gold_required > $city_res->gold)
            {
                $enough_gold = "fas fa-times icon-color-red";
                $allowed = "KO";
            }
            return ([$allowed, $food_required, $enough_food, $wood_required, $enough_wood, $rock_required, $enough_rock, $steel_required, $enough_steel, $gold_required, $enough_gold, $duration]);
        }

        public function craft_item(Request $request)
        {
            $city_id = session()->get('city_id');
            $already_crafting = DB::table('waiting_items')->where('city_id', '=', $city_id)->value('id');
            if ($already_crafting !== null)
                return "Greg le Hackeur";
            $item_id = $request['item_id'];
            $quantity = $request['quantity'];
            $item = DB::table('forge')
            ->where('id', '=', $item_id)
            ->first();
            if ($item === null)
                return ("item_error");
            $city_res = DB::table('cities')
            ->where('id', '=', $city_id)
            ->first();
            $finishing_date = ($item->duration * $quantity) + time();
            $food_required = 0;
            $wood_required = 0;
            $rock_required = 0;
            $gold_required = 0;
            $steel_required = 0;
            $res_required = explode(";", $item->price);
            foreach ($res_required as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "W")
                    $wood_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "R")
                    $rock_required = intval(substr($amount, 0, -1)) * $quantity;
                else if ($amount[-1] == "S")
                    $steel_required = intval(substr($amount, 0, -1)) * $quantity;
                else
                    $gold_required = intval(substr($amount, 0, -1)) * $quantity;
            }
            if ($food_required > $city_res->food || $wood_required > $city_res->wood || $rock_required > $city_res->rock || $steel_required > $city_res->steel || $gold_required > $city_res->gold)
                return ;
            DB::table('cities')
            ->where('id', '=', $city_id)
            ->update(['food' => $city_res->food - $food_required, 'wood' => $city_res->wood - $wood_required, 'rock' => $city_res->rock - $rock_required, 'steel' => $city_res->steel - $steel_required, 'gold' => $city_res->gold - $gold_required]);
            $id = DB::table('waiting_items')
            ->insertGetId(["city_id" => $city_id, "item_id" => $item->id, "finishing_date" => $finishing_date, "quantity" => $quantity]);
            return ("good");
        }
    }
?>
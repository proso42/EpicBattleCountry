<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class HomeController extends Controller
    {
        public function index()
        {
            if (!isset($_GET['activeTab'])|| ($_GET['activeTab'] !== "eco" && $_GET['activeTab'] !== "army" && $_GET['activeTab'] !== "religious" && $_GET['activeTab'] !== "tech"))
                $first_active_tab = "eco";
            else
                $first_active_tab = $_GET['activeTab'];
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
            $prod_table_class = session()->get('prod_table_status');
            if ($prod_table_class == null)
            {
                session()->put(["prod_table_status" => 1]);
                $prod_table_class = 1;
            }
            $item_table_class = session()->get('item_table_status');
            if ($item_table_class == null)
            {
                session()->put(["item_table_status" => 1]);
                $item_table_class = 1;
            }
            $unit_table_class = session()->get('unit_table_status');
            if ($unit_table_class == null)
            {
                session()->put(["unit_table_status" => 1]);
                $unit_table_class = 1;
            }
            $tables_class = ["prod" => $prod_table_class, "item" => $item_table_class, "unit" => $unit_table_class];
            $city_name = $city->name;
            $food = $city->food;
            $compact_food = $food;
            $max_food = $city->max_food;
            $food_prod = $city->food_prod;
            $wood = $city->wood;
            $compact_wood = $wood;
            $max_wood = $city->max_wood;
            $wood_prod = $city->wood_prod;
            $rock = $city->rock;
            $compact_rock = $rock;
            $max_rock = $city->max_rock;
            $rock_prod = $city->rock_prod;
            $steel = $city->steel;
            $compact_steel = $steel;
            $max_steel = $city->max_steel;
            $steel_prod = $city->steel_prod;
            $gold = $city->gold;
            $compact_gold = $gold;
            $max_gold = $city->max_gold;
            $gold_prod = $city->gold_prod;
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
            $all_items = DB::table('forge')
            ->get();
            $items_owned = array();
            foreach ($all_items as $item)
            {
                $item_format = preg_replace('/\s/', '_', $item->name);
                $item_quantity = $city->$item_format;
                if ($item_quantity > 0)
                    array_push($items_owned, ["name" => $item->name, "quantity" => $item_quantity]);
            }
            $all_units = DB::table('units')->get();
            $city_units = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $units_owned = array();
            foreach ($all_units as $unit)
            {
                $unit_format = preg_replace('/\s/', '_', $unit->name);
                $unit_quantity = $city_units->$unit_format;
                if ($unit_quantity > 0)
                    array_push($units_owned, ["name" => $unit->name, "quantity" => $unit_quantity]);
            }
            $waiting_buildings = DB::table('waiting_buildings')
            ->where('city_id', '=', $city_id)
            ->get();
            $waiting_techs = DB::table('waiting_techs')
            ->where('city_id', '=', $city_id)
            ->get();
            $waiting_item = DB::table('waiting_items')
            ->where('city_id', '=', $city_id)
            ->first();
            $waiting_unit = DB::table('waiting_units')
            ->where('city_id', '=', $city_id)
            ->first();
            $waiting_list = array();
            foreach ($waiting_buildings as $build)
            {
                $building_name = DB::table($build->type)
                ->where('id', '=', $build->building_id)
                ->value('name');
                array_push($waiting_list, ["wait_id" => $build->id,"type" => "building", "name" => $building_name, "duration" => $build->finishing_date - time()]);
            }
            foreach ($waiting_techs as $tech)
            {
                $tech_name = DB::table('techs')
                ->where('id', '=', $tech->tech_id)
                ->value('name');
                array_push($waiting_list, ["wait_id" => $tech->id, "type" => "tech", "name" => $tech_name, "duration" => $tech->finishing_date - time()]);
            }
            if ($waiting_item !== null)
                array_push($waiting_list, ["wait_id" => $waiting_item->id, "type" => "item", "name" => DB::table('forge')->where('id', '=', $waiting_item->item_id)->value('name'), "duration" => $waiting_item->finishing_date - time(), "quantity" => $waiting_item->quantity]);
            if ($waiting_unit !== null)
                array_push($waiting_list, ["wait_id" => $waiting_unit->id, "type" => "unit", "name" => DB::table('units')->where('id', '=', $waiting_unit->unit_id)->value('name'), "duration" => $waiting_unit->finishing_date - time(), "quantity" => $waiting_unit->quantity]);
            return view('home', compact('food', 'compact_food', 'max_food', 'food_prod', 'wood', 'compact_wood' ,'max_wood', 'wood_prod', 'rock', 'compact_rock', 'max_rock', 'rock_prod', 'steel', 'compact_steel', 'max_steel', 'steel_prod', 'gold', 'compact_gold', 'max_gold', 'gold_prod', 'city_name', 'waiting_list', 'items_owned', 'units_owned', 'tables_class'));
        }

        private function save_choice(Request $request)
        {
            $section = $request['section'];
            $val = $request['val'];
            if (($section !== 'prod' && $section !== 'item' && $section !== 'unit') || ($val !== 0 && $val !== 1))
                return ;
            //session()->put([$section . "_table_status" => $val]);
            return ;
        }
    }

?>
<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\App;

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
            $is_admin = DB::table('users')->where('id', '=', $user_id)->value("is_admin");
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
            $util = Common::get_utilities($user_id, $city_id);
            $all_items = DB::table('forge')
            ->get();
            $items_owned = array();
            foreach ($all_items as $item)
            {
                $item_format = preg_replace('/\s/', '_', $item->name);
                $item_quantity = $util->$item_format;
                if ($item_quantity > 0)
                    array_push($items_owned, ["name" => trans('item.' . $item_format), "quantity" => $item_quantity]);
            }
            $all_units = DB::table('units')->get();
            $all_mercenaries = DB::table('mercenaries')->get();
            $city_units = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $city_mercenaries = DB::table('cities_mercenaries')->where('city_id', '=', $city_id)->first();
            $units_owned = array();
            foreach ($all_units as $unit)
            {
                $unit_format = preg_replace('/\s/', '_', $unit->name);
                $unit_quantity = $city_units->$unit_format;
                if ($unit_quantity > 0)
                    array_push($units_owned, ["name" => trans('unit.' . $unit_format), "quantity" => $unit_quantity]);
            }
            foreach($all_mercenaries as $mercenary)
            {
                $quantity = $city_mercenaries->$mercenary->name;
                if ( $quantity > 0)
                    array_push($units_owned, ["name" => trans('mercenaries.' $mercenary->name), "quantity" => $quantity]);
            }
            if ($util->Cheval > 0)
                array_push($units_owned, ["name" => trans('mount.Cheval'), "quantity" => $util->Cheval]);
            if ($util->Likorne > 0)
                array_push($units_owned, ["name" => trans('mount.Likorne'), "quantity" => $util->Likorne]);
            if ($util->Bouc_de_guerre > 0)
                array_push($units_owned, ["name" => trans('mount.Bouc_de_guerre'), "quantity" => $util->Bouc_de_guerre]);
            if ($util->Loup > 0)
                array_push($units_owned, ["name" => trans('mount.Loup'), "quantity" => $util->Loup]);
            if ($util->Dragon)
                array_push($units_owned, ["name" => trans('mount.Dragon'), "quantity" => $util->Dragon]);
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
            $traveling_units = DB::table('traveling_units')
            ->where('city_id', '=', $city_id)
            ->get();
            $user_cities = DB::table('cities')
            ->select('id', 'name')
            ->where('owner', '=', $user_id)
            ->where('id', '!=', $city_id)
            ->get();
            $waiting_list = array();
            foreach ($waiting_buildings as $build)
            {
                $building_name = DB::table($build->type)
                ->where('id', '=', $build->building_id)
                ->value('name');
                array_push($waiting_list, ["wait_id" => $build->id,"type" => "building", "name" => trans('building.' . preg_replace('/\s/', '_', $building_name)), "duration" => $build->finishing_date - time()]);
            }
            foreach ($waiting_techs as $tech)
            {
                $tech_name = DB::table('techs')
                ->where('id', '=', $tech->tech_id)
                ->value('name');
                array_push($waiting_list, ["wait_id" => $tech->id, "type" => "tech", "name" => trans('tech.' . preg_replace('/\s/', '_', $tech_name)), "duration" => $tech->finishing_date - time()]);
            }
            if ($waiting_item !== null)
                array_push($waiting_list, ["wait_id" => $waiting_item->id, "type" => "item", "name" => trans('item.' . preg_replace('/\s/', '_', DB::table('forge')->where('id', '=', $waiting_item->item_id)->value('name'))), "duration" => $waiting_item->finishing_date - time(), "quantity" => $waiting_item->quantity]);
            if ($waiting_unit !== null)
                array_push($waiting_list, ["wait_id" => $waiting_unit->id, "type" => "unit", "name" => trans('unit.' . preg_replace('/\s/', '_', DB::table('units')->where('id', '=', $waiting_unit->unit_id)->value('name'))), "duration" => $waiting_unit->finishing_date - time(), "quantity" => $waiting_unit->quantity]);
            foreach ($traveling_units as $travel)
            {
                $mission_name = DB::table('traveling_missions')->where('id', '=', $travel->mission)->value('mission');
                if ($travel->mission == 6)
                    $allow_interrupt = 0;
                else
                    $allow_interrupt = 1;
                if ($travel->mission == 7)
                    $type = "move_units";
                else if ($travel->mission == 5)
                    $type = "battle";
                else
                    $type = "explo";
                array_push($waiting_list, ["allow_interrupt" => $allow_interrupt, "wait_id" => $travel->id, "type" => $type, "name" => trans('exploration.' . $mission_name), "duration" => $travel->finishing_date - time()]);
            }
            $enemy_on_the_way = [];
            $all_attacks = DB::table('traveling_units')->where('mission', '=', 5)->where('ending_point', '=', $util->x_pos . "/" . $util->y_pos)->get();
            foreach ($all_attacks as $attack)
                array_push($enemy_on_the_way, ["wait_id" => $attack->id, "duration" => $attack->finishing_date - time()]);
            return view('home', compact('is_admin', 'util', 'waiting_list', 'items_owned', 'units_owned', 'tables_class', 'user_cities', 'enemy_on_the_way'));
        }

        public function switch_city(Request $request)
        {
            $user_id = session()->get('user_id');
            $new_city_id = $request['new_city_id'];
            $city_id_db = DB::table('cities')->where('id', '=', $new_city_id)->where('owner', '=', $user_id)->value('id');
            if ($new_city_id == $city_id_db)
            {
                session(['city_id' => $city_id_db]);
                return 0;
            }
            else
                return 1;
        }

        public function save_choice(Request $request)
        {
            $section = $request['section'];
            $val = $request['val'];
            if (($section != 'prod' && $section != 'item' && $section != 'unit') || ($val < 0 || $val > 1))
                return 1;
            session()->put([$section . "_table_status" => $val]);
            return 0;
        }

        public function rename_city(Request $request)
        {
            if (!isset($request['new_name']))
                return 1;
            $new_name = trim($request['new_name']);
            $size = strlen($new_name);
            if ($size < 4 || $size > 20)
                return 1;
            $alpha = 0;
            for ($i = 0; $i < $size; $i++)
            {
                if (($new_name[$i] >= 'a' && $new_name[$i] <= 'z') || ($new_name[$i] >= 'A' && $new_name[$i] <= 'Z'))
                    $alpha++;
            }
            if ($alpha == 0)
                return 1;
            $already_taken = DB::table('cities')->where('name', '=', $new_name)->first();
            if ($already_taken == null)
            {
                $city_id = session()->get('city_id');
                $user_id = session()->get('user_id');
                DB::table('cities')->where('id', '=', $city_id)->where('owner', '=', $user_id)->update(['name' => $new_name]);
                return 0;
            }
            else
                return 2;
        }
    }

?>
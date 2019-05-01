<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class InterruptController extends Controller
    {
        public function interrupt(Request $request)
        {
            $type_canceled = $request['type'];
            $wait_id = $request['wait_id'];
            $city_id = session()->get('city_id');
            if ($type_canceled == "building")
                $table = "waiting_buildings";
            else if ($type_canceled == "tech")
                $table = "waiting_techs";
            else if ($type_canceled == "item")
                return $this->interrupt_item($city_id);
            else if ($type_canceled == "unit")
                return $this->interrupt_unit($city_id);
            else if ($type_canceled == "explo" || $type_canceled == "move_units" || $type_canceled == "battle")
                return $this->interrupt_explo($city_id, $wait_id);
            else
                return ("interrupt error : bad type canceled");
            $elem_canceled = DB::table($table)
            ->where('id', '=', $wait_id)
            ->first();
            $niv = $elem_canceled->next_level - 1;
            $basic_price = 0;
            $levelup_price = 0;
            if ($type_canceled == "building")
            {
                $build_info = DB::table($elem_canceled->type)
                ->where('id', '=', $elem_canceled->building_id)
                ->first();
                $basic_price = $build_info->basic_price;
                $levelup_price = $build_info->levelup_price;
            }
            else if ($type_canceled == "tech")
            {
                $tech_info = DB::table("techs")
                ->where('id', '=', $elem_canceled->tech_id)
                ->first();
                $basic_price = $tech_info->basic_price;
                $levelup_price = $tech_info->levelup_price;
            }
            $res_refund = explode(";", $basic_price);
            $food_refund = 0;
            $wood_refund = 0;
            $rock_refund = 0;
            $steel_refund = 0;
            $gold_refund = 0;
            foreach ($res_refund as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_refund = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $levelup_price);
                else if ($amount[-1] == "W")
                    $wood_refund = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $levelup_price);
                else if ($amount[-1] == "R")
                    $rock_refund = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $levelup_price);
                else if ($amount[-1] == "S")
                    $steel_refund = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $levelup_price);
                else
                    $gold_refund = $this->get_exp_value($niv, intval(substr($amount, 0, -1)), $levelup_price);
            }
            $city_infos = DB::table('cities')
            ->select('food', 'max_food', 'wood', 'max_wood', 'rock', 'max_rock', 'steel', 'max_steel', 'gold', 'max_gold')
            ->where('id', '=', $city_id)
            ->first();
            if ($food_refund + $city_infos->food > $city_infos->max_food)
                $food_refund = $city_infos->max_food;
            else
                $food_refund += $city_infos->food;
            if ($wood_refund + $city_infos->wood > $city_infos->max_wood)
                $wood_refund = $city_infos->max_wood;
            else
                $wood_refund += $city_infos->wood;
            if ($rock_refund + $city_infos->rock > $city_infos->max_rock)
                $rock_refund = $city_infos->max_rock;
            else
                $rock_refund += $city_infos->rock;
            if ($steel_refund + $city_infos->steel > $city_infos->max_steel)
                $steel_refund = $city_infos->max_steel;
            else
                $steel_refund += $city_infos->steel;
            if ($gold_refund + $city_infos->gold > $city_infos->max_gold)
                $gold_refund = $city_infos->max_gold;
            else
                $gold_refund += $city_infos->gold;
            DB::table('cities')
            ->where('id', '=', $city_id)
            ->update(['food' => $food_refund, 'wood' => $wood_refund, 'rock' => $rock_refund, 'steel' => $steel_refund, 'gold' => $gold_refund]);
            DB::table($table)
            ->where('id', '=', $wait_id)
            ->delete();
            $infos = ['type' => "build/tech", 'food' => $food_refund, 'wood' => $wood_refund, 'rock' => $rock_refund, 'steel' => $steel_refund, 'gold' => $gold_refund];
            return ($infos);
        }

        private function interrupt_explo($city_id, $wait_id)
        {
            $user_id = session()->get('user_id');
            $explo = DB::table('traveling_units')->where('id', '=', $wait_id)->first();
            if ($explo->mission == 6)
                return ("interrupt error : cannot cancel mission : 6");
            $mission_name = trans('exploration.' . DB::table('traveling_missions')->where('id', '=', $explo->mission)->value("mission"));
            $starting_point = $explo->ending_point;
            $ending_point = $explo->starting_point;
            $mission = 6;
            $time_elapsed = time() - ($explo->finishing_date - $explo->traveling_duration);
            $finishing_date = $time_elapsed + time();
            $content = trans('exploration.your_mission') . " ($mission_name) " . trans('common.on') . " $explo->ending_point " . trans('exploration.end_of_content');
            DB::table('messages')->insert(["seen" => 0, "sender" => "notification", "target" => $user_id, "target_city" => $city_id, "title" => trans('exploration.canceled_mission'), "content" => $content, "sending_date" => time()]);
            DB::table('traveling_units')->where('id', '=', $wait_id)->delete();
            DB::table('traveling_units')->insert(["city_id" => $explo->city_id, "owner" => $explo->owner, "starting_point" => $starting_point, "ending_point" => $ending_point, "units" => $explo->units, "res_taken" => $explo->res_taken, "traveling_duration" => $time_elapsed, "finishing_date" => $finishing_date, "mission" => 6]);
            $infos = ["type" => "explo", "duration" => $time_elapsed, "mission_name" => trans('exploration.Go_Home')];
            return ($infos);
        }

        private function interrupt_item($city_id)
        {
            $item = DB::table('waiting_items')
            ->where('city_id', '=', $city_id)
            ->first();
            $price = DB::table('forge')
            ->where('id', '=', $item->item_id)
            ->value('price');
            $res_refund = explode(";", $price);
            $food_refund = 0;
            $wood_refund = 0;
            $rock_refund = 0;
            $steel_refund = 0;
            $gold_refund = 0;
            foreach ($res_refund as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_refund = intval(substr($amount, 0, -1)) * $item->quantity;
                else if ($amount[-1] == "W")
                    $wood_refund = intval(substr($amount, 0, -1)) * $item->quantity;
                else if ($amount[-1] == "R")
                    $rock_refund = intval(substr($amount, 0, -1)) * $item->quantity;
                else if ($amount[-1] == "S")
                    $steel_refund = intval(substr($amount, 0, -1)) * $item->quantity;
                else
                    $gold_refund = intval(substr($amount, 0, -1)) * $item->quantity;
            }
            $city_infos = DB::table('cities')
            ->select('food', 'max_food', 'wood', 'max_wood', 'rock', 'max_rock', 'steel', 'max_steel', 'gold', 'max_gold')
            ->where('id', '=', $city_id)
            ->first();
            if ($food_refund + $city_infos->food > $city_infos->max_food)
                $food_refund = $city_infos->max_food;
            else
                $food_refund += $city_infos->food;
            if ($wood_refund + $city_infos->wood > $city_infos->max_wood)
                $wood_refund = $city_infos->max_wood;
            else
                $wood_refund += $city_infos->wood;
            if ($rock_refund + $city_infos->rock > $city_infos->max_rock)
                $rock_refund = $city_infos->max_rock;
            else
                $rock_refund += $city_infos->rock;
            if ($steel_refund + $city_infos->steel > $city_infos->max_steel)
                $steel_refund = $city_infos->max_steel;
            else
                $steel_refund += $city_infos->steel;
            if ($gold_refund + $city_infos->gold > $city_infos->max_gold)
                $gold_refund = $city_infos->max_gold;
            else
                $gold_refund += $city_infos->gold;
            DB::table('cities')
            ->where('id', '=', $city_id)
            ->update(['food' => $food_refund, 'wood' => $wood_refund, 'rock' => $rock_refund, 'steel' => $steel_refund, 'gold' => $gold_refund]);
            DB::table('waiting_items')
            ->where('id', '=', $item->id)
            ->delete();
            $infos = ['type' => "item", 'food' => $food_refund, 'wood' => $wood_refund, 'rock' => $rock_refund, 'steel' => $steel_refund, 'gold' => $gold_refund];
            return ($infos);
        }

        private function interrupt_unit($city_id)
        {
            $unit = DB::table('waiting_units')
            ->where('city_id', '=', $city_id)
            ->first();
            if ($unit == null)
                return ("interrupt error A : nothing found in database");
            $unit_price = DB::table('units')
            ->where('id', '=', $unit->unit_id)
            ->first();
            if ($unit->unit_id == null)
                return ("interrupt error B : nothing found in database");
            $res_refund = explode(";", $unit_price->basic_price);
            $food_refund = 0;
            $wood_refund = 0;
            $rock_refund = 0;
            $steel_refund = 0;
            $gold_refund = 0;
            foreach ($res_refund as $res => $amount)
            {
                if ($amount[-1] == "F")
                    $food_refund = intval(substr($amount, 0, -1)) * $unit->quantity;
                else if ($amount[-1] == "W")
                    $wood_refund = intval(substr($amount, 0, -1)) * $unit->quantity;
                else if ($amount[-1] == "R")
                    $rock_refund = intval(substr($amount, 0, -1)) * $unit->quantity;
                else if ($amount[-1] == "S")
                    $steel_refund = intval(substr($amount, 0, -1)) * $unit->quantity;
                else
                    $gold_refund = intval(substr($amount, 0, -1)) * $unit->quantity;
            }
            if ($unit_price->mount > 0)
                $mount_name = preg_replace('/\s/', "_", DB::table('mounts')->where('id', '=', $unit_price->mount)->value('mount_name'));
            $city_infos = DB::table('cities')
            ->where('id', '=', $city_id)
            ->first();
            if ($food_refund + $city_infos->food > $city_infos->max_food)
                $food_refund = $city_infos->max_food;
            else
                $food_refund += $city_infos->food;
            if ($wood_refund + $city_infos->wood > $city_infos->max_wood)
                $wood_refund = $city_infos->max_wood;
            else
                $wood_refund += $city_infos->wood;
            if ($rock_refund + $city_infos->rock > $city_infos->max_rock)
                $rock_refund = $city_infos->max_rock;
            else
                $rock_refund += $city_infos->rock;
            if ($steel_refund + $city_infos->steel > $city_infos->max_steel)
                $steel_refund = $city_infos->max_steel;
            else
                $steel_refund += $city_infos->steel;
            if ($gold_refund + $city_infos->gold > $city_infos->max_gold)
                $gold_refund = $city_infos->max_gold;
            else
                $gold_refund += $city_infos->gold;
            $refound_tab = ['food' => $food_refund, 'wood' => $wood_refund, 'rock' => $rock_refund, 'steel' => $steel_refund, 'gold' => $gold_refund];
            $infos = ['type' => 'unit', 'food' => $food_refund, 'wood' => $wood_refund, 'rock' => $rock_refund, 'steel' => $steel_refund, 'gold' => $gold_refund];
            if ($unit_price->mount > 0)
            {
                $refound_tab[$mount_name] = $city_infos->$mount_name + $unit->quantity;
                $infos['type'] = 'mounted_unit';
                $infos['mount'] = ['mount_id' => trans('mount.' . $mount_name), "quantity" => $refound_tab[$mount_name]];
            }
            $infos["item"] = [];
            if ($unit_price->item_needed !== "NONE")
            {
                $all_items = DB::table('forge')->get();
                $items = explode(";", $unit_price->item_needed);
                foreach ($items as $item => $item_id)
                {
                    $item_name = preg_replace('/\s/', "_", $all_items[$item_id - 1]->name);
                    $refound_tab[$item_name] = $city_infos->$item_name + $unit->quantity;
                    $infos['item'][] = ["item_name" => trans('item.' . $item_name), "quantity" => $refound_tab[$item_name]];
                }
            }
            DB::table('cities')
            ->where('id', '=', $city_id)
            ->update($refound_tab);
            DB::table('waiting_units')
            ->where('id', '=', $unit->id)
            ->delete();
            return ($infos);
        }

        private function get_exp_value($niv, $basic_value, $levelup)
        {
            $final_value = intval($basic_value);
            for ($i = 1; $i <= $niv; $i++)
                $final_value *= $levelup;
            return floor($final_value);
        }
            
    }

?>
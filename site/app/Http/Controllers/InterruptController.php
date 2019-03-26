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
            $city_id = session()->get('city_id');
            if ($type_canceled == "building")
                $table = "waiting_buildings";
            else if ($type_canceled == "tech")
                $table = "waiting_techs";
            else
                return $this->interrupt_item($city_id);
            $wait_id = $request['wait_id'];
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
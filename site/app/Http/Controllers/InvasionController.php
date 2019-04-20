<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class InvasionController extends Controller
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
            $is_admin = DB::table('users')->where('id', '=', $user_id)->value("is_admin");
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
            $city_unit = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $info_unit = [];
            foreach ($city_unit as $unit => $val)
            {
                if ($unit == "id" || $unit == "city_id" || $unit == "owner")
                    continue;
                if ($val <= 0)
                    continue ;
                array_push($info_unit, ["ref" => $unit, "name" => trans("unit." . $unit), "quantity" => $val]);
            }
            $user_cities = DB::table('cities')->select('name')->where('owner', '=', $user_id)->where('id', '!=', $city_id)->get();
            return view('invasion', compact('is_admin', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold', 'info_unit', 'user_cities'));
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

        public function calculate_move_units(Request $request)
        {
            $unit = $request['units'];
            $city_target = $request['city_target'];
            $user_id = session()->get('user_id');
            $city_id = session()->get('city_id');
            $city_target_info = DB::table('cities')->where('name', '=', $city_target)->where('owner', '=', $user_id)->first();
            if ($city_target_info == null || $city_target_info->id == $city_id)
                return ("invasion error : bad city");
            $city_units = DB::table('cities_units')->where('city_id', '=', $city_id)->first();
            $min_speed = 0;
            foreach ($units as $unit => $quantity)
            {
                if ($city_units->$unit < $quantity)
                    return ("invasion error : bad unit");
                $unit_name_format = preg_replace('/_/', " ", $unit);
                $unit_speed = DB::table('units')->where('name', '=', $unit_name_format)->value('speed');
                if ($unit_speed < $min_speed)
                    $min_speed = $unit_speed;
            }
            $city_coord = DB::table('cities')->select('x_pos', 'y_pos')->where('id', '=', $city_id)->first();
            $travel_duration = $this->sec_to_date(((abs($city_coord->x_pos - $city_target_info->x_pos) + abs($city_coord->y_pos - $city_target_info->y_pos)) * $min_speed));
            return ($travel_duration);
        }
    }

?>
<?php

    namespace App\Models;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class Common
    {
        public static function get_targetable_cities($city_id, $user_id, $x_pos, $y_pos)
        {
            $cartographer = DB::table('cities_buildings')->where('city_id', '=', $city_id)->value('Cartographe');
            if ($cartographer <= 0)
                return null;
            return (DB::table('cities')
            ->select('name', 'x_pos', 'y_pos')
            ->where('x_pos' ,'>=', $x_pos - $cartographer)
            ->where('x_pos', '<=', $x_pos + $cartographer)
            ->where('y_pos', '>=', $y_pos - $cartographer)
            ->where('y_pos', '<=', $y_pos + $cartographer)
            ->where('owner', '!=', $user_id)
            ->get());
        }

        public static function get_visible_cells($x_pos, $y_pos, $cartographer)
        {
            return (DB::table('map')
            ->where('x_pos' ,'>=', $x_pos - $cartographer)
            ->where('x_pos', '<=', $x_pos + $cartographer)
            ->where('y_pos', '>=', $y_pos - $cartographer)
            ->where('y_pos', '<=', $y_pos + $cartographer)
            ->orderBy('y_pos', 'desc')
            ->orderBy('x_pos', 'asc')
            ->get());
        }

        public static function get_utilities($user_id, $city_id)
        {
            $util = DB::table('cities')
            ->where('owner', '=', $user_id)
            ->where('id', '=', $city_id)
            ->first();
            if ($util->food > 999999)
                $util->compact_food = Common::compact_nb($util->food);
            else
                $util->compact_food = $util->food;
            if ($util->wood > 999999)
                $util->compact_wood = substr($util->wood, 0, 5) . '...';
            else
                $util->compact_wood = $util->wood;
            if ($util->rock > 999999)
                $util->compact_rock = substr($util->rock, 0, 5) . '...';
            else
                $util->compact_rock = $util->rock;
            if ($util->steel > 999999)
                $util->compact_steel = substr($util->steel, 0, 5) . '...';
            else
                $util->compact_steel = $util->steel;
            if ($util->gold > 999999)
                $util->compact_gold = substr($util->gold, 0, 5) . '...';
            else
                $util->compact_gold = $util->gold;
            $msg_not_seen = DB::table('messages')
            ->where('target_city', '=', $city_id)
            ->where('seen', '=', 0)
            ->count();
            $util->msg_not_seen = $msg_not_seen;
            return $util;
        }

        public static function sec_to_date($duration)
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
            if ($duration < 24)
                return ($duration . " h " . $new_duration);
            if ($duration % 24 > 0)
                $new_duration = ($duration % 24) . " h " . $new_duration;
            $duration = floor($duration / 24);
            if ($new_duration !== "")
                return ($duration . " j " . $new_duration);
            else
                return ($duration . " j");
        }

        public static function get_exp_value($niv, $basic_value, $levelup)
        {
            $final_value = intval($basic_value);
            for ($i = 1; $i <= $niv; $i++)
                $final_value *= $levelup;
            return floor($final_value);
        }

        public static function compact_nb($nb)
        {
            // 5000         -> 5000
            // 50000        -> 50k
            // 500000       -> 500k
            // 5000000      -> 5M
            // 5120000      -> 5.1M
            if ($nb <= 9999)
                return $nb;
            else if ($nb <999999)
               return ((floor($nb / 1000)) . "k");
            else
            {
                $nb = floor($nb/10000);
                if ($nb % 100 == 0)
                    return ($nb . "M");
                else
                    return ($nb . "." . ($nb % 100) . "M");

            }
                
        }
    }

?>
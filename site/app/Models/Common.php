<?php

    namespace App\Models;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class Common
    {
        public static function get_visible_cities($city_id, $user_id, $x_pos, $y_pos)
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

        public static function get_utilities($user_id, $city_id)
        {
            $util = DB::table('cities')
            ->where('owner', '=', $user_id)
            ->where('id', '=', $city_id)
            ->first();
            if ($util->food > 999999)
                $util->compact_food = substr($util->food, 0, 5) . '...';
            if ($util->wood > 999999)
                $util->compact_wood = substr($util->wood, 0, 5) . '...';
            if ($util->rock > 999999)
                $util->compact_rock = substr($util->rock, 0, 5) . '...';
            if ($util->steel > 999999)
                $util->compact_steel = substr($util->steel, 0, 5) . '...';
            if ($util->gold > 999999)
                $util->compact_gold = substr($util->gold, 0, 5) . '...';
            return $util;
        }
    }

?>
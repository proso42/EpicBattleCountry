<?php

    namespace App\Models;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class Common
    {
        private function get_visible_cities($city_id, $user_id)
        {
            $carthorapher = DB::table('cities_buildings')->where('city_id', '=', $city_id)->value('Cartographe');
            if ($carthorapher <= 0)
                return null;
            return (DB::table('cities')
            ->select('name', 'x_pos', 'y_pos')
            ->where('x_pos' ,'>=', $city->x_pos - $cartographer)
            ->where('x_pos', '<=', $city->x_pos + $cartographer)
            ->where('y_pos', '>=', $city->y_pos - $cartographer)
            ->where('y_pos', '<=', $city->y_pos + $cartographer)
            ->where('owner', '!=', $user_id)
            ->get());
        }
    }

?>
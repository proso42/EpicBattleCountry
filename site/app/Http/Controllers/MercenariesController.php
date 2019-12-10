<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class MercenariesController extends Controller
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
            $util = Common::get_utilities($user_id, $city_id);
            $allowed = DB::table('cities_buildings')
            ->where('city_id', '=', $city_id)
            ->value('Taverne');
            if ($allowed == 0)
                return view('taverne', compact('is_admin', 'allowed' ,'util'));
            else
            {
                $slots = $this->get_available_mercenaries($city_id, $allowed);
                return view('taverne', compact('is_admin', 'allowed' ,'util', 'slots'));
            }
        }

        private function get_available_mercenaries($city_id, $tavern_lvl)
        {
            $slot1 = null;
            $slot2 = null;
            $slot3 = null;
            $slots = DB::table('cities')
            ->select('tavern_slot1', 'tavern_slot2', 'tavern_slot3')
            ->where('city_id', '=', $city_id)
            ->first();
            $info_merc1 = DB::table('mercenaries')
            ->select('name', 'gold_price', 'diamond_price', 'life', 'speed', 'power', 'storage', 'cool_down')
            ->where('id', '=', $slots->tavern_slot1)
            ->first();
            $info_slot1 = DB::table('mercenaries_cool_down')
            ->where('city_id', '=', $city_id)
            ->where('tavern_slot', '=', 1)
            ->first();
            if ($info_slot1)
                $slot1 = ['cool_down' => Common::sec_to_date($info_slot1->finishing_date - time()), 'available' => 0];
            else
            {
                $slot1 = ['name' => trans('mercenaries.' . $info_merc1->name), 
                'gold' => $info_merc1->gold_price,
                'diamond' => $info_merc1->diamond_price,
                'life' => $info_merc1->life,
                'speed' => $info_merc1->speed,
                'power' => $info_merc1->power,
                'storage' => $info_merc1->storage,
                'cool_down' => Common::sec_to_date($info_merc1->cool_down),
                'available' => 1];
            }
            if ($tavern_lvl >= 10)
            {
                $info_merc2 = DB::table('mercenaries')
                ->select('name', 'gold_price', 'diamond_price', 'life', 'speed', 'power', 'storage', 'cool_down')
                ->where('id', '=', $slots->tavern_slot2)
                ->first();
                $info_slot2 = DB::table('mercenaries_cool_down')
                ->where('city_id', '=', $city_id)
                ->where('tavern_slot', '=', 2)
                ->first();
                if ($info_slot2)
                    $slot2 = ['cool_down' => Common::sec_to_date($info_slot2->finishing_date - time()), 'available' => 0];
                else
                {
                    $slot2 = ['name' => trans('mercenaries.' . $info_merc2->name), 
                    'gold' => $info_merc2->gold_price,
                    'diamond' => $info_merc2->diamond_price,
                    'life' => $info_merc2->life,
                    'speed' => $info_merc2->speed,
                    'power' => $info_merc2->power,
                    'storage' => $info_merc2->storage,
                    'cool_down' => Common::sec_to_date($info_merc2->cool_down),
                    'available' => 1];
                }
            }
            if ($tavern_lvl >= 25)
            {
                $info_merc3 = DB::table('mercenaries')
                ->select('name', 'gold_price', 'diamond_price', 'life', 'speed', 'power', 'storage', 'cool_down')
                ->where('id', '=', $slots->tavern_slot3)
                ->first();
                $info_slot3 = DB::table('mercenaries_cool_down')
                ->where('city_id', '=', $city_id)
                ->where('tavern_slot', '=', 3)
                ->first();
                if ($info_slot3)
                    $slot3 = ['cool_down' => Common::sec_to_date($info_slot3->finishing_date - time()), 'available' => 0];
                else
                {
                    $slot3 = ['name' => trans('mercenaries.' . $info_merc3->name), 
                    'gold' => $info_merc3->gold_price,
                    'diamond' => $info_merc3->diamond_price,
                    'life' => $info_merc3->life,
                    'speed' => $info_merc3->speed,
                    'power' => $info_merc3->power,
                    'storage' => $info_merc3->storage,
                    'cool_down' => Common::sec_to_date($info_merc3->cool_down),
                    'available' => 1];
                }
            }
            $slots = ['slot1' => $slot1, 'slot2' => $slot2, 'slot3' => $slot3];
            return $slots;
        }
    }
?>
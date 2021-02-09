<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Cache;

    class QuestsController extends Controller
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
            $util = Common::get_utilities($user_id, $city_id);
            $quests = DB::table('city_quests')->where('city_id', '=', $city_id)->get();
            foreach ($quests as $quest)
            {
                $quest_type = "";
                if ($quest->type == 1)
                    $quest_type = "dungeons";
                else if ($quest->type == 2)
                    $quest_type = "dragons_cave";
                else if ($quest->type == 3)
                    $quest_type = "Giant_spider_nest";
                else if ($quest->type == 4)
                    $quest_type = "heroic_quests";
                else
                    $quest_type = "error";
                $difficulty = DB::table($quest_type)->where('id', '=', $quest->scenario)->value('difficulty');
                $quest->difficulty = $difficulty;
            }
            return view('quests', compact('is_admin', 'util', 'quests'));
        }

        public function give_up(Request $request)
        {
            $city_id = session()->get('city_id');
            $quest_id = $request['quest_id'];
            $check_quest = DB::table('city_quests')->where('city_id', '=', $city_id)
            ->where('id', '=', $quest_id)
            ->first();
            if ($check_quest)
            {
                $quest_deleted = DB::table('city_quests')->where('city_id', '=', $city_id)
                ->where('id', '=', $quest_id)
                ->delete();
                if ($quest_deleted)
                    return (["Result" => "Success"]);
                else
                    return (["Result" => "Error", "Reason" => "Quest deleting request has failed !"]);
            }
            else
                return (["Result" => "Error", "Reason" => "Quest_id does not match with city_id or quest not found in database."]);
        }

        
    }

?>
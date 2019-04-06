<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class MessagesController extends Controller
    {
        public function index()
        {
            if (!isset($_GET['activeTab']) || ($_GET['activeTab'] !== "notif" && $_GET['activeTab'] !== "sended" && $_GET['activeTab'] !== "received" && $_GET['activeTab'] !== "blocked"))
                $first_active_tab = "notif";
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
            $city_build = DB::table('cities_buildings')
            ->where('owner', '=', $user_id)
            ->where('city_id', '=', $city_id)
            ->first();
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
            $city_x = $city->x_pos;
            $city_y = $city->y_pos;
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
            $notifications = [];
            $msg_sended = [];
            $msg_received = [];
            $all_user_msgs = DB::table('messages')->where('target', '=', $user_id)->orWhere('sender', '=', $user_id)->get();
            //dd($all_user_msgs);
            $all_users = DB::table('users')->get();
            $notif_alert = 0;
            $msg_received_alert = 0;
            foreach ($all_user_msgs as $msg)
            {
                if ($msg->sender == "notification")
                {
                    array_push($notifications, ["id" => $msg->id, "seen" => $msg->seen, "sender" => "Notification", "title" => $msg->title, "content" => $msg->content, "date" => $msg->sending_date]);
                    if ($msg->seen == 0)
                        $notif_alert++;
                }
                else if ($msg->sender == $user_id)
                    array_push($msg_sended, ["id" => $msg->id, "seen" => $msg->seen, "sender" => $all_users[$msg->sender]->login, "title" => $msg->title, "content" => $msg->content, "date" => $msg->sending_date]);
                else if ($msg->target == $user_id)
                {
                    array_push($msg_received, ["id" => $msg->id, "seen" => $msg->seen, "target" => $all_users[$msg->target]->login, "title" => $msg->title, "content" => $msg->content, "date" => $msg->sending_date]);
                    if ($msg->seen == 0)
                        $msg_received_alert++;
                }
            }
            $users_blocked = [];
            $all_users_blocked = DB::table('user_msg_blocked')->where('user_id', '=', $user_id)->get();
            foreach ($all_users_blocked as $blocked)
                array_push($users_blocked, $all_users[$blocked->user_blocked]['login']);
            return view('messages', compact('first_active_tab', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold', 'notifications', 'msg_sended', 'msg_received', 'users_blocked', 'notif_alert', 'msg_received_alert'));
        }

        public function seen(Request $request)
        {
            $msg_id = $request['id'];
            DB::table('messages')->where("id", '=', $msg_id)->update(["seen" => 1]);
            return 0;
        }

        public function unlock_user(Request $request)
        {
            $login = $request['login'];
            $user_blocked_id = DB::table('users')->where('login', '=', $login)->value('id');
            DB::table('user_msg_blocked')->where('user_blocked', '=', $user_blocked_id)->where('user_id', '=', session()->get('user_id'))->delete();
            return 0;
        }

        public function block_user(Request $request)
        {
            $login = $request['login'];
            $user_to_block = DB::table('users')->where('login', '=', $login)->value('id');
            if ($user_to_block === null)
                return 1;
            DB::table('user_msg_blocked')->insert(["user_blocked" => $user_to_block, "user_id" => session()->get('user_id')]);
            return 0;
        }
    }

?>
<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
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
            $is_admin = DB::table('users')->where('id', '=', $user_id)->value("is_admin");
            $city_build = DB::table('cities_buildings')
            ->where('owner', '=', $user_id)
            ->where('city_id', '=', $city_id)
            ->first();
            $util = Common::get_utilities($user_id, $city_id);
            $notifications = [];
            $msg_sended = [];
            $msg_received = [];
            $all_user_msgs = DB::table('messages')->where('target', '=', $user_id)->orWhere('sender', '=', $user_id)->orderByRaw('sending_date DESC')->get();
            $all_users = DB::table('users')->get();
            $notif_alert = 0;
            $msg_received_alert = 0;
            foreach ($all_user_msgs as $msg)
            {
                if ($msg->sender == "notification" && $msg->target_city == $city_id)
                {
                    array_push($notifications, ["id" => $msg->id, "seen" => $msg->seen, "sender" => "Notification", "title" => $msg->title, "content" => $msg->content, "date" => date("d/m", $msg->sending_date)]);
                    if ($msg->seen == 0)
                        $notif_alert++;
                }
                else if ($msg->sender == $user_id)
                    array_push($msg_sended, ["id" => $msg->id, "seen" => $msg->seen, "sender" => $all_users[$this->get_id($all_users, $msg->sender)]->login, "title" => $msg->title, "content" => $msg->content, "date" => date("d/m", $msg->sending_date)]);
                else if ($msg->target == $user_id && $msg->sender != "notification")
                {
                    array_push($msg_received, ["id" => $msg->id, "seen" => $msg->seen, "target" => $all_users[$this->get_id($all_users, $msg->target)]->login, "title" => $msg->title, "content" => $msg->content, "date" => date("d/m", $msg->sending_date)]);
                    if ($msg->seen == 0)
                        $msg_received_alert++;
                }
            }
            $users_blocked = [];
            $all_users_blocked = DB::table('user_msg_blocked')->where('user_id', '=', $user_id)->get();
            foreach ($all_users_blocked as $blocked)
                array_push($users_blocked, $all_users[$blocked->user_blocked]['login']);
            return view('messages', compact('is_admin', 'first_active_tab', 'util', 'notifications', 'msg_sended', 'msg_received', 'users_blocked', 'notif_alert', 'msg_received_alert'));
        }

        private function get_id($all_users, $id)
        {
            foreach ($all_users as $user)
            {
                if ($user->id == $id)
                    return $id;
            }
            return 0;
        }

        public function seen(Request $request)
        {
            $user_id = session()->get('user_id');
            $msg_id = $request['msg_id'];
            DB::table('messages')->where("id", '=', $msg_id)->where("target", '=', $user_id)->update(["seen" => 1]);
            return 0;
        }

        public function delete_msg(Request $request)
        {
            $user_id = session()->get('user_id');
            $msg_id = $request['msg_id'];
            DB::table('messages')->where('id', '=', $msg_id)->where('target', '=', $user_id)->orWhere("sender", '=', $user_id)->delete();
            return (0);
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
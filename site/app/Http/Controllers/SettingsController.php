<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class SettingsController extends Controller
    {
        public function index()
        {
            $user_infos = DB::table('users')
            ->select('email', 'login', 'race', 'is_premium')
            ->where('id', '=', session()->get('user_id'))
            ->first();
            $complete_email = $user_infos->email;
            if (strlen($complete_email) > 18)
                $user_email = substr($complete_email, 0, 15) . "...";
            $complete_login = $user_infos->login;
            if (strlen($complete_login) > 17)
                $user_login = substr($complete_login, 0, 14) . "...";
            $user_race = $user_infos->race;
            if ($user_infos->is_premium)
                $is_premium = "Oui";
            else
                $is_premium = "Non";
            return view('settings', compact('complete_email', 'user_email', 'complete_login', 'user_login', 'user_race', 'is_premium', 'user_race'));
        }

        public function reset_login(Request $request)
        {
            $new_login = $request['new_login'];
            $existing_login = DB::table('users')
            ->select('id')
            ->where('login', '=', $new_login)
            ->first();
            if ($existing_login == null)
            {
                DB::table('users')
                ->where('id', '=', session()->get('user_id'))
                ->update(['login' => $new_login]);
                return (0);
            }
            return (1);
        }

        public function send_email_reset_email(Request $request)
        {
            $new_email = $request['new_email'];
            $existing_email = DB::table('users')
            ->select('id')
            ->where('email', '=', $new_email)
            ->first();
            if ($existing_email == null)
            {
                $email_token = $this->gen_confirmation_email_token();
                $link = "http://www.epicbattlecorp.fr/validate_email?token=" . $email_token;
                DB::table('email_validation')
                ->insert(
                    array('user_id' => session()->get('user_id'), 'user_email' => $new_email, 'email_token' => $email_token, 'status' => 'Waiting')
                );
                $cmd = "cd /home/boss/www/scripts ; node ./send_email_reset_email.js " . $new_email  . " \"" . $link . "\"";
                exec($cmd);
                return (0);
            }
            return (1);
        }

        private function gen_confirmation_email_token()
        {
            $email_token = "";
            for ($i = 0; $i < 25; $i++)
            {
                $rdm1 = rand(0,2);
                if ($rdm1 == 0)
                    $email_token .= chr(rand(48,57));
                else if ($rdm1 == 1)
                    $email_token .= chr(rand(65,90));
                else
                    $email_token .= chr(rand(97,122));
            }
            return $email_token;
        }
    }

?>
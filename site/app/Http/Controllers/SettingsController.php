<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class SettingsController extends Controller
    {
        public function index()
        {
            $user_id =  $user_id = session()->get('user_id');
            $user_infos = DB::table('users')
            ->select('lang', 'email', 'login', 'race', 'is_premium', 'is_admin')
            ->where('id', '=', $user_id)
            ->first();
            $city_id = session()->get('city_id');
            if ($city_id === null)
            {
                $city_id = DB::table('cities')
                ->where('owner', '=', $user_id)
                ->where('is_capital', '=', 1)
                ->value('id');
                session()->put(['city_id' => $city_id]);
            }
            $is_admin = $user_infos->is_admin;
            $user_lang = $user_infos->lang;
            if ($user_lang == 'fr')
                $alt_lang = 'en';
            else
                $alt_lang = 'fr';
            $complete_email = $user_infos->email;
            if (strlen($complete_email) > 18)
                $user_email = substr($complete_email, 0, 15) . "...";
            else
                $user_email = $complete_email;
            $complete_login = $user_infos->login;
            if (strlen($complete_login) > 17)
                $user_login = substr($complete_login, 0, 14) . "...";
            else
                $user_login = $complete_login;
            $user_race = trans('common.' . DB::table('races')
            ->where('id', '=', $user_infos->race)
            ->value('race_name'));
            if ($user_infos->is_premium)
                $is_premium = "Oui";
            else
                $is_premium = "Non";
            if (session()->get('csrf_token_login') !== null)
                session()->forget('csrf_token_login');
            if (session()->get('csrf_token_email') !== null)
                session()->forget('csrf_token_email');
            if (session()->get('csrf_token_password') !== null)
                session()->forget('csrf_token_password');
            $csrf_token_login = csrf_token();
            $csrf_token_email = csrf_token();
            $csrf_token_password = csrf_token();
            session()->put(['csrf_token_login' => $csrf_token_login, 'csrf_token_email' => $csrf_token_email, 'csrf_token_password' => $csrf_token_password]);
            $util = Common::get_utilities($user_id, $city_id);
            return view('settings', compact('is_admin', 'user_lang', 'alt_lang', 'complete_email', 'user_email', 'complete_login', 'user_login', 'user_race', 'is_premium', 'csrf_token_login', 'csrf_token_email', 'csrf_token_password', 'util'));
        }

        public function reset_login(Request $request)
        {
            $_token = $request['_token'];
            $csrf_token_login = session()->get('csrf_token_login');
            if ($csrf_token_login === null)
                return redirect('/home');
            else if ($_token !== $csrf_token_login)
            {
                session()->forget('csrf_token_login');
                return redirect('/home');
            }
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
            $_token = $request['_token'];
            $csrf_token_email = session()->get('csrf_token_email');
            if ($csrf_token_email === null)
                return redirect('/home');
            else if ($_token !== $csrf_token_email)
            {
                session()->forget('csrf_token_email');
                return redirect('/home');
            }
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
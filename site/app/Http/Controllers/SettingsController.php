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
            ->select('lang', 'email', 'login', 'race', 'is_premium')
            ->where('id', '=', session()->get('user_id'))
            ->first();
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
            $user_race = trans(DB::table('races')
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
            $city = DB::table('cities')
            ->where('owner', '=', session()->get('user_id'))
            ->where('id', '=', session()->get('city_id'))
            ->first();
            $city_name = $city->name;
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
            return view('settings', compact('user_lang', 'alt_lang', 'complete_email', 'user_email', 'complete_login', 'user_login', 'user_race', 'is_premium', 'csrf_token_login', 'csrf_token_email', 'csrf_token_password', 'food', 'compact_food', 'max_food', 'wood', 'compact_wood' ,'max_wood', 'rock', 'compact_rock', 'max_rock', 'steel', 'compact_steel', 'max_steel', 'gold', 'compact_gold', 'max_gold'));
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
<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class SignupController extends Controller
    {
        public function index()
        {
            return view('signup');
        }

        public function check_infos()
        {
            if (!isset($_GET['login']) || !isset($_GET['email']) || !isset($_GET['sponsor']))
                return 404;
            $existing_login = DB::table('users')
            ->select('login')
            ->where('login', '=', $_GET['login'])->first();
            if (isset($existing_login->login) && $existing_login->login == $_GET['login'])
                return 1;
            $existing_email = DB::table('users')
            ->select('email')
            ->where('email', '=', $_GET['email'])->first();
            if (isset($existing_email->email) && $existing_email->email == $_GET['email'])
                return 3;
            if ($_GET['sponsor'] == null)
                return 0;
            $existing_sponsor = DB::table('users')
            ->select('login')
            ->where('login', '=', $_GET['sponsor'])->first();
            if (!isset($existing_sponsor->login))
                return 4;
            return 0;
        }

        public function register(Request $request)
        {
            if ($request['password'] !== $request['password2'])
                return view('signup_internal_fail');
            $id_race = DB::table('races')
            ->select('id')
            ->where('race_name', '=', $request['race'])
            ->first();
            $crypted_password = password_hash($request['password'], PASSWORD_BCRYPT);
            $email = $request['email'];
            $login = $request['login'];
            $city = $request['city'];
            $city_x_pos = rand(-2000, 2000);
            $city_y_pos = rand(-2000, 2000);
            while (1)
            {    
                $ret = DB::table('cities')
                ->where('x_pos', '=', $city_x_pos)
                ->where('y_pos', '=', $city_y_pos)
                ->first();
                if ($ret === null)
                    break;
                else
                {
                    $city_x_pos = rand(-2000, 2000);
                    $city_y_pos = rand(-2000, 2000);
                }
            }
            $user_id = DB::table('users')
            ->insertGetId(
                array('login' => $login, 'email' => $email, 'password' => $crypted_password, 'remember_token' => $request['_token'], 'created_at' => time(), 'race' => $id_race->id)
            );
            $city_id = DB::table('cities')
            ->insertGetId(
                array(
                    'name' => $city,
                    'owner' => $user_id,
                    'x_pos' => $city_x_pos,
                    'y_pos' => $city_y_pos,
                    'is_capital' => 1,
                    'food' => 500,
                    'max_food' => 2500,
                    'wood' => 350,
                    'max_wood' => 2500,
                    'rock' => 350,
                    'max_rock' => 2500,
                    'steel' => 100,
                    'max_steel' => 2500,
                    'gold' => 25,
                    'max_gold' => 2500)
            );
            $link = $this->gen_confirmation_email_link();
            $email_token = explode('=', $link)[1];
            DB::table('email_validation')
            ->insert(
                array('user_id' => $user_id, 'user_email' => $email, 'email_token' => $email_token, 'status' => 'Waiting')
            );
            $cmd = "cd /home/boss/www/scripts ; node ./send_mail.js " . $login . " " . $email  . " \"" . $link . "\"";
            exec($cmd);
            return view('register_success', compact('email'));
        }

        private function gen_confirmation_email_link()
        {
            $link = "http://www.epicbattlecorp.fr/validate_email?token=";
            for ($i = 0; $i < 25; $i++)
            {
                $rdm1 = rand(0,2);
                if ($rdm1 == 0)
                    $link .= chr(rand(48,57));
                else if ($rdm1 == 1)
                    $link .= chr(rand(65,90));
                else
                    $link .= chr(rand(97,122));
            }
            return $link;
        }
    }

?>
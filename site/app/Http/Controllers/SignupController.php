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
            $crypted_password = bcrypt($request['password']);
            $email = $request['email'];
            $login = $request['login'];
            $user_id = DB::table('users')
            ->insertGetId(
                array('login' => $login, 'email' => $email, 'password' => $crypted_password, 'token' => $request['_token'], 'created_at' => time(), 'race' => $id_race->id)
            );
            $link = $this->gen_confirmation_email_link();
            DB::table('email_validation')
            ->insert(
                array('user_id' => $user_id, 'user_email' => $email, 'link' => $link, 'status' => 'Waiting')
            );
            $output = array();
            $err = 0;
            $cmd = "cd /home/boss/www/scripts ; node ./send_mail.js " . $login . " " . $email  . " \"" . $link . "\"";
            exec($cmd, $output, $err);
            dd($err);
            return view('register_success', compact('email', 'cmd', 'ret', 'err'));
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
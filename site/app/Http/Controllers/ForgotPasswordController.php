<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class ForgotPasswordController extends Controller
    {
        public function index()
        {
            return view('forgot_password');
        }

        public function send_reset_password_email(Request $request)
        {
            $email = $request['email'];
            $user = DB::table('users')
            ->select('id')
            ->where('email', '=', $email)
            ->first();
            if ($user == null)
                return view('send_reset_password_email', compact('email'));
            else
            {
                $reset_token = $this->gen_reset_token();
                $link = "http://www.epicbattlecorp.fr/reset_password?user_email=" . $email . "&reset_token=" . $reset_token;
                DB::table('reseting_password')
                ->insert(['user_id' => $user->id, 'user_email' => $email, 'reset_token' => $reset_token, 'status' => 'Waiting']);
                $cmd = "cd /home/boss/www/scripts ; node ./send_reset_password_email.js " . $email  . " \"" . $link . "\"";
                exec($cmd);
                return view('send_reset_password_email', compact('email'));
            }
        }

        private function gen_reset_token()
        {
            $reset_token = "";
            for ($i = 0; $i < 48; $i++)
            {
                $rdm1 = rand(0,4);
                if ($rdm1 == 0)
                    $reset_token .= chr(rand(48,57));
                else if ($rdm1 == 1)
                    $reset_token .= chr(rand(65,90));
                else if ($rdm1 == 2)
                    $reset_token .= chr(rand(97,122));
                else if ($rdm1 == 3)
                    $reset_token .= chr(rand(35,36));
                else
                    $reset_token .= chr(rand(40,46));
            }
            return $reset_token;
        }
    }

?>
<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class SigninController extends Controller
    {
        public function index()
        {
            return view('signin');
        }

        public function try_to_login()
        {
            $account = $_GET['account'];
            $password = $_GET['password'];
            $auth = DB::table('users')
            ->select('email_verified_at', 'password')
            ->where('login', '=', $account)
            ->first();
            return $crypted_password;
            if ($auth == null)
            {
                $auth = DB::table('users')
                ->select('email_verified_at', 'password')
                ->where('email', '=', $account)
                ->first();
            }
            if ($auth == null || !password_verify($password, $auth->password))
                return 1;
            else if ($auth->email_verified_at == null)
                return 2;
            else
                return 0;
        }

        public function login()
        {
            Auth::login();
            return redirect('/');
        }
    }

?>
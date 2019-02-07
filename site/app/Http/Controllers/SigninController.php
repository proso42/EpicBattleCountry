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
            $crypted_password = bcrypt($_GET['password']);
            $auth = DB::table('users')
            ->select('id', 'email_verified_at')
            ->where('login', '=', $account)
            ->where('password', '=', $crypted_password)
            ->first();
            return $_GET;
            if ($auth == null)
            {
                $auth = DB::table('users')
                ->select('id', 'email_verified_at')
                ->where('email', '=', $account)
                ->where('password', '=', $crypted_password)
                ->first();
            }
            if ($auth == null)
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
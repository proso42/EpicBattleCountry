<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

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
            ->select('id', 'email_verified_at', 'password')
            ->where('login', '=', $account)
            ->first();
            if ($auth == null)
            {
                $auth = DB::table('users')
                ->select('id', 'email_verified_at', 'password')
                ->where('email', '=', $account)
                ->first();
            }
            if ($auth == null || !password_verify($password, $auth->password))
                return 1;
            else if ($auth->email_verified_at == null)
                return 2;
            else
            {
                session(['user_id' => $auth->id]);
                /*session(['passwd' => $auth->password]);
                session(['account' => $account]);*/
                return 0;
            }
        }

        public function login()
        {
            Auth::loginUsingId($user_id);
            echo (Auth::check());
            /*echo (session()->get('passwd'));
            echo (session()->get('account'));*/
            dd($_SESSION);
            return redirect('tmp_home');
        }
    }

?>
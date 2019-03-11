<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class SetNewPasswordController extends Controller
    {
        public function index()
        {
            if (!isset($_GET['_token']) || session()->get('csrf_token_password') === null || session()->get('new_password') === null)
            {
                if (session()->get('new_password') !== null)
                    session()->forget('new_password');
                if (session()->get('csrf_token_password') !== null)
                    session()->forget('csrf_token_password');
                return redirect('/home');
            }
            $csrf_token_password = csrf_token();
            session()->forget('csrf_token_password');
            session()->put(['csrf_token_password' => $csrf_token_password]);
            return view('set_new_password', compact('csrf_token_password'));
        }

        public function check_new_password(Request $request)
        {
            $_token = $request['_token'];
            $csrf_token_password = session()->get('csrf_token_password');
            if ($csrf_token_password === null)
                return redirect('/home');
            else if ($csrf_token_password !== $_token)
            {
                session()->forget('csrf_token_password');
                return redirect('/home');
            }
            $new_password = $request['new_password'];
            $current_password = DB::table('users')
            ->where('id', '=', session()->get('user_id'))
            ->value('password');
            if (password_verify($new_password, $current_password))
                return (1);
            else
            {
                if (session()->get('new_password') !== null)
                    session()->forget('new_password');
                session()->put(['new_password' => $new_password]);
                return (0);
            }
        }

        public function update_password(Request $request)
        {
            $_token = $request['_token'];
            $csrf_token_password = session()->get('csrf_token_password');
            if ($csrf_token_password === null)
                return redirect('/home');
            else if ($csrf_token_password !== $_token)
            {
                session()->forget('csrf_token_password');
                return redirect('/home');
            }
            $user_id = session()->get('user_id');
            $new_password = session()->get('new_password');
            $confirm_new_password = $request['confirm_new_password'];
            $user_current_password = $request['user_current_password'];
            $db_current_password = DB::table('users')
            ->where('id', '=', $user_id)
            ->value('password');
            if ($new_password !== $confirm_new_password)
                return (1);
            else if (!password_verify($user_current_password, $db_current_password))
                return (2);
            else if (password_verify($confirm_new_password, $db_current_password))
                return (3);
            DB::table('users')
            ->where('id', '=', $user_id)
            ->update(['password' => password_hash($confirm_new_password, PASSWORD_BCRYPT)]);
            session()->forget('new_password');
            session()->forget('csrf_token_password');
            return (0);
        }
    }

?>
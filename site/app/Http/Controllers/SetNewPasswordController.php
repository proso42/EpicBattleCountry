<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class SetNewPasswordControllerController extends Controller
    {
        public function index()
        {
            return view('set_new_password');
        }

        public function check_new_password(Request $request)
        {
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
            return (0);
        }
    }

?>
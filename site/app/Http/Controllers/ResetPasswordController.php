<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class ResetPasswordController extends Controller
    {
        public function index()
        {
            if (!isset($_GET['user_email']) || !isset($_GET['reset_token']))
                return view('reset_password_error');
            $email = $_GET['user_email'];
            $reset_token = $_GET['reset_token'];
            $authorization = DB::table('reseting_password')
            ->select('user_id')
            ->where('user_email', '=', $email)
            ->where('reset_token', '=', $reset_token)
            ->where('status', '=', 'Waiting')
            ->first();
            if ($authorization == null)
                return view('reset_password_error');
            else
            {
                $user_id = $authorization->user_id;
                return view('reset_password', compact('user_id'));
            }
        }

        public function set(Request $request)
        {
            if (!isset($request['password']) || !isset($request['password2']) || !isset($request['user_id']))
                return 1;
            $user_id = $request['user_id'];
            $new_password = $request['password'];
            $confirm_new_password = $request['password2'];
            if ($new_password !== $confirm_new_password)
                return 2;
            $crypted_password = password_hash($new_password, PASSWORD_BCRYPT);
            DB::table('users')
            ->where('id', '=', $user_id)
            ->update(['password' => $crypted_password]);
            DB::table('reseting_password')
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 'Waiting')
            ->delete();
            return view('reset_password_success');
        }
    }

?>
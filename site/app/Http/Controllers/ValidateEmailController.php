<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class ValidateEmailController extends Controller
    {
        public function index()
        {
            $url_email_token = '';
            foreach ($_GET as $key => $value)
            {
                if ($key == 'token')
                {
                    $url_email_token = $value;
                    break;
                }
            }
            if ($url_email_token == '')
                return view('validate_email_failed');
            $db_email_validation = DB::table('confirmation_email')
                ->select('email_token', 'user_email')
                ->where('email_token', '=', $url_email_token)
                ->where('status', '=', 'Waiting')
                ->first();
            $user_email = $db_email_validation->user_email;
            $db_email_token = $db_email_validation->email_token;
            if ($url_email_token == $db_email_token)
            {
                DB::table('confirmation_email')
                ->update('status', '=', 'Validated')
                ->where('email_token', '=', $url_email_token);
                DB::table('users')
                ->update('email_verified_at', '=', time())
                ->where('email', '=', $user_email);
                return redirect('validate_email_success', 'user_email');
            }
            else
                return view('validate_email_failed');
        }
    }

?>
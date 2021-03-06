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
            $db_email_validation = DB::table('email_validation')
                ->select('user_id', 'email_token', 'user_email')
                ->where('email_token', '=', $url_email_token)
                ->where('status', '=', 'Waiting')
                ->first();
            if ($db_email_validation == null)
                return view('validate_email_failed');
            $user_email = $db_email_validation->user_email;
            $db_email_token = $db_email_validation->email_token;
            $user_id = $db_email_validation->user_id;
            if ($url_email_token == $db_email_token)
            {
                DB::table('email_validation')
                ->where('email_token', '=', $url_email_token)
                ->update(['status' => 'Validated']);
                DB::table('users')
                ->where('id', '=', $user_id)
                ->update(['email' => $user_email, 'email_verified_at' => time()]);
                return view('validate_email_success', compact('user_email'));
            }
            else
                return view('validate_email_failed');
        }
    }

?>
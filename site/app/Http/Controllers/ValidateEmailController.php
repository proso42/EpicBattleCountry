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
                ->select('email_token', 'user_email')
                ->where('email_token', '=', $url_email_token)
                ->where('status', '=', 'Waiting')
                ->first();
            dd($db_email_validation);
            $user_email = $db_email_validation->user_email;
            $db_email_token = $db_email_validation->email_token;
            if ($url_email_token == $db_email_token)
            {
                DB::table('email_validation')
                ->where('email_token', '=', $url_email_token)
                ->update(['status' => 'Validated']);
                DB::table('users')
                ->where('email', '=', $user_email)
                ->update(['email_verified_at' => time()]);
                return redirect('validate_email_success', 'user_email');
            }
            else
                return view('validate_email_failed');
        }
    }

?>
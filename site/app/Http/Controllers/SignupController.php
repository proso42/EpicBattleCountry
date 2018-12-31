<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class SignupController extends Controller
    {
        public function index()
        {
            return view('signup');
        }

        public function check_infos()
        {
            $existing_login = DB::table('users')
            ->select('login')
            ->where('login', '=', $_GET['login'])->first();
            if ($existing_login->login == $_GET['login'])
                return 1;
            $existing_email = DB::table('users')
            ->select('email')
            ->where('email', '=', $_GET['email'])->first();
            if ($existing_email->email == $_GET['email'])
                return 3;
            $existing_sponsor = DB::table('users')
            ->select('login')
            ->where('login', '=', $_GET['sponsor'])->first();
            if (!$existing_sponsor->sponsor)
                return 4;
            return 0;
        }

        public function register(Request $request)
        {
            dd($request->all());
        }
    }

?>
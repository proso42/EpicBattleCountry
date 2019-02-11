<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class SettingsController extends Controller
    {
        public function index()
        {
            $user_infos = DB::table('users')
            ->select('email', 'login', 'race')
            ->where('id', '=', session()->get('user_id'))
            ->first();
            $user_email = $user_infos->email;
            $user_login = $user_infos->login;
            $user_race = $user_infos->race;
            return view('settings', compact('user_email', 'user_login', 'user_race'));
        }

        public function reset_login(Request $request)
        {
            $new_login = $request['new_login'];
            $existing_login = DB::table('users')
            ->select('id')
            ->where('login', '=', $new_login)
            ->first();
            if ($existing_login == null)
            {
                DB::table('users')
                ->where('id', '=', session()->get('user_id'))
                ->update(['login' => $new_login]);
                return ($new_login);
            }
            return (1);
        }
    }

?>
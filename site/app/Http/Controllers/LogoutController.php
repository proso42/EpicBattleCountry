<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class LogoutController extends Controller
    {
        public function logout()
        {
            Auth::logout();
            return redirect('/signin');
        }
    }

?>
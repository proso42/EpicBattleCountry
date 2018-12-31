<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;

    class SignupController extends Controller
    {
        public function index()
        {
            return view('signup');
        }

        public function check_infos()
        {
            if (isset($_GET['login']))
            {
                if ($_GET['login'] === 'bbb')
                    return 1;
                else
                    return 0;
            }
            return 2;
        }

        public function register(Request $request)
        {
            dd($request->all());
        }
    }

?>
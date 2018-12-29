<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    //use Illuminate\Http\Request;

    class SignupController extends Controller
    {
        public function index()
        {
            return view('signup');
        }

        public function register(Request $request)
        {
            dd($request->all());
        }
    }

?>
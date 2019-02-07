<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class ForgotPasswordController extends Controller
    {
        public function index()
        {
            return view('forgot_password');
        }
    }

?>
<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class TmpController extends Controller
    {
        public function index()
        {
            echo Auth::check();
            return view('tmp_home');
        }
    }

?>
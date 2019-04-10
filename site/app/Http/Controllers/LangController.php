<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class LangController extends Controller
    {
        public function switch_fr()
        {
            session(['lang' => 'fr']);
            return redirect('/settings');
        }

        public function switch_en()
        {
            session(['lang' => 'en']);
            return redirect('/settings');
        }
    }

?>
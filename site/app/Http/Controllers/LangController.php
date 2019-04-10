<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\App;

    class LangController extends Controller
    {
        public function switch_lang($lang)
        {
            if ($lang != 'fr' && $lang != 'en')
                $lang = 'fr';
            if (session()->has('lang'))
                session(['lang' => $lang]);
            else
                session()->put(['lang' => $lang]);
            if (session()->has('user_id'))
                DB::table('users')->where('id', '=', session()->get('user_id'))->update(['lang' => $lang]);
            return redirect('/settings');
        }
    }

?>
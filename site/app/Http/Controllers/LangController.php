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
            App::setLocale($lang);
            return redirect('/settings');
        }
    }

?>
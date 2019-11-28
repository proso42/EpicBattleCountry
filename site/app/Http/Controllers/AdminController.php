<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Models\Common;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\App;

    class AdminController extends Controller
    {
        public function index()
        {
            $user_id = session()->get('user_id');
            $city_id = session()->get('city_id');
            if ($city_id === null)
            {
                $city_id = DB::table('cities')
                ->where('owner', '=', $user_id)
                ->where('is_capital', '=', 1)
                ->value('id');
                session()->put(['city_id' => $city_id]);
            }
            $is_admin = DB::table('users')->where('id', '=', $user_id)->value("is_admin");
            $util = Common::get_utilities($user_id, $city_id);
            $all_users = DB::table('users')->get('login');
            dd($all_users);
            return view('admin', compact('is_admin', 'util', 'all_users'));
        }
    }

?>
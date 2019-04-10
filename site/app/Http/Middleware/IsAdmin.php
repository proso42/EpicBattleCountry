<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IsLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user_id = session()->get('user_id');
        $is_admin = DB::table('users')->where('id', '=', $user_id)->value('is_admin');
        if ($is_admin == 0)
            return redirect('/home');
        else
            return $next($request);
    }
}
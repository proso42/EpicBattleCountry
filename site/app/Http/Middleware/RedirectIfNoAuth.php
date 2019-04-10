<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class RedirectIfNoAuth
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
        if (session()->has('user_id'))
            $locale = DB::table('users')->where('id', '=', session()->get('user_id')->value('lang'));
        else if (session()->has('lang'))
            $locale = session()->get('lang');
        else
        {
            $locale = 'fr';
            session()->put(['lang' => 'fr']);
        }
        App::setLocale($locale);
        if (Auth::check())
            return $next($request);
        else
            return redirect('/signin');
    }
}
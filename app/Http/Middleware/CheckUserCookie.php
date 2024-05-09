<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Users;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CheckUserCookie
{
    public function handle($request, Closure $next)
    {
        //die(print_R(Cookie::get()));
        if(Cookie::get('userId') and !Session::get('user') )
        {
            $id = explode( '|', decrypt(Cookie::get('userId') ?? '', false) )[1];
            Session::put('user', DB::table('users')->where('userId', $id)->first());
        }

        return $next($request);
    }
}

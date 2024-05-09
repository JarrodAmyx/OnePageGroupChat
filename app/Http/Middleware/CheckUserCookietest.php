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
        if(Cookie::get('userCode'))
        {
            $code = explode( '|', decrypt(Cookie::get('userCode') ?? '', false) )[1];
            if( !Session::get('user') and $user = $this->getUserFromCookie( $code ) ) {
                Session::put('user', $user);
            }
        }

        return $next($request);
    }
    
    private function getUserFromCookie($code)
    {
        $cookie = DB::table('cookies')->where('cookieCode', $code)->first();
        if ($cookie) {
            return DB::table('users')->where('userId', $cookie->userId)->first();
        }
        return null;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
        if (Auth::guard($guard)->check() && $guard == 'admin') {
            return redirect('/admin/dashboard');
        }

//        if (Auth::guard($guard)->guest()) {
//            if ($request->ajax() || $request->wantsJson()) {
//                return response('Unauthorized.', 401);
//            } else {
//                $loginPath = [
//                    'admin' => '/admin/login',
//                ];
//                $url = empty($guard) ? '/login' : (isset($loginPath[$guard]) ? $loginPath[$guard] : '/login');
//
//                return redirect()->guest($url);
//            }
//        }

        return $next($request);
    }
}

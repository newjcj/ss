<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $member = $request->session()->get('user', '');
//        if($member == '') {
//
//            $return_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];//当前请求的url
//            return redirect('/admin/user/login/index?return_url=' . urlencode($return_url));
//        }

        return $next($request);
    }

}

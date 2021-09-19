<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware {
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) {
        if (! $request->expectsJson()) {
            return route('admin_login');
        }
    }

//    public function handle($request, Closure $next, array ...$guards) {
//        // 检测token是否有效
//        if (Auth::guard(...$guards)->guest()) {
//            return response(['msg' => '无效的token', 'code' => 1], 401);
//        }
//        return $next($request);
//    }
}

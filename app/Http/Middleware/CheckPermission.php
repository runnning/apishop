<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next):mixed
    {
        //验证用户是否具有请求权限
        $user=auth('api')->user();
        if(!$user?->can($request->route()?->getName())){
            abort(403,'没有该功能访问权限!');
        }
        return $next($request);
    }
}

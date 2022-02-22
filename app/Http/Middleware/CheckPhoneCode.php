<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPhoneCode
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'code'=>'required',
            'phone'=>'required|regex:/^1[3-9]\d{9}$/'
        ],[
            'code.required'=>'验证码 不能为空'
        ]);

        //验证code是否正确
        if(cache('phone-code_'.$request->input('phone'))!==$request->input('code')){
            abort(400,'验证码或手机号错误');
        }

        return $next($request);
    }
}

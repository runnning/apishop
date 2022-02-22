<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendSms;
use App\Http\Controllers\BaseController;
use App\Mail\SendCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Overtrue\EasySms\EasySms;

class BindController extends BaseController
{
    public function __construct()
    {
        $this->middleware('check.phone.code')->only('updatePhone');
        $this->middleware('check.email.code')->only('updateEmail');
    }

    /**
     * 获取邮箱验证码
    */
    public function emailCode(Request $request): \Dingo\Api\Http\Response
    {
        $request->validate([
            'email'=>'required|email|unique:users'
        ]);

        //发送验证码到邮件
        Mail::to($request->input('email'))->queue(new SendCode($request->input('email')));
        return $this->response->noContent();
    }

    /**
     *更新邮箱
     * @throws \Exception
     */
    public function updateEmail(Request $request): \Dingo\Api\Http\Response
    {
        $request->validate([
            'email'=>'unique:users'
        ]);
        //更新邮箱
        $user=auth('api')->user();
        $user->email=$request->input('email');
        $user->save();
        return $this->response->noContent();
    }

    /**
     * 获取手机验证码
     * @throws \Exception
     */
    public function phoneCode(Request $request): \Dingo\Api\Http\Response
    {
        $request->validate([
            'phone'=>'required|regex:/^1[3-9]\d{9}$/|unique:users'
        ]);

        //发送短信事件
        SendSms::dispatch($request->input('phone'));

        return $this->response->noContent();
    }

    /**
     *更新手机号
     * @throws \Exception
     */
    public function updatePhone(Request $request): \Dingo\Api\Http\Response
    {
        $request->validate([
            'phone'=>'unique:users'
        ]);
        //更新手机号
        $user=auth('api')->user();
        $user->phone=$request->input('phone');
        $user->save();
        return $this->response->noContent();
    }
}

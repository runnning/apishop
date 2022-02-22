<?php

use Dingo\Api\Routing\Router;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OssController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\BindController;
use App\Http\Controllers\Auth\PasswordResetController;
$api=app(Router::class);
$params=[
    'middleware' => [
        'api.throttle',
        'bindings',//路由模型绑定注入
        'serializer:array' //减少transformer的包裹层
    ],
    'limit' => 60,
    'expires' => 1
];
$api->version('v1',$params, function ($api) {
    //路由组
    $api->group(['prefix'=>'auth'],function ($api){
        //注册
        $api->post('register',[RegisterController::class,'store']);
        //登录
        $api->post('login',[LoginController::class,'login']);
        //通过邮箱获取验证码
        $api->post('reset/password/email/code',[PasswordResetController::class,'emailCode']);
        //提交邮箱和验证码,修改密码
        $api->patch('reset/password/email',[PasswordResetController::class,'resetPasswordByEmail']);
        //需要登录的路由
        $api->group(['middleware'=>'api.auth'],  static function ($api){
            //退出
            $api->post('logout',[LoginController::class,'logout']);
            //刷新token
            $api->post('refresh',[LoginController::class,'refresh']);
            //阿里云oss token
            $api->get('oss/token',[OssController::class,'token']);
            //修改密码
            $api->post('password/update',[PasswordController::class,'updatePassword']);
            //发送邮件验证码
            $api->post('email/code',[BindController::class,'emailCode']);
            //更新邮箱
            $api->patch('email/update',[BindController::class,'updateEmail']);
            //发送手机验证码
            $api->post('phone/code',[BindController::class,'phoneCode']);
            //更新手机
            $api->patch('phone/update',[BindController::class,'updatePhone']);
        });
    });

});

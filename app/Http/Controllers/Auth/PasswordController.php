<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends BaseController
{
    /**
     * 修改密码
    */
    public function updatePassword(Request $request): \Dingo\Api\Http\Response
    {
        $request->validate([
            'old_password'=>'required|min:6|max:16',
            'password'=>'required|min:6|max:16|confirmed'
        ],[
            'old_password.required'=>'旧密码 不能为空',
            'old_password.min'=>'旧密码 最少6个字符',
            'old_password.max'=>'旧密码 最多16个字符',
        ]);
        $old_password=$request->input('old_password');
        $user=auth('api')->user();
        //验证旧密码是否正确
        //php自带函数验证
        //if (!password_verify($old_password,$user)){
        // return $this->response->errorBadRequest('旧密码不正确!');
        //}
        //laravel方法认证
        if(!Hash::check($old_password,$user?->password)){
             $this->response->errorBadRequest('旧密码不正确!');
        }

        //更新用户密码
        $user->password=bcrypt($request->input('password'));
        $user->save();
        return  $this->response->noContent();
    }
}

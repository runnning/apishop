<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends BaseController
{
    /**
     * 用户注册
    */
    public function store(RegisterRequest $request): \Dingo\Api\Http\Response
    {
        $user=new User();
        $user->name=$request->input('name');
        $user->email=$request->input('email');
        $user->password=bcrypt($request->input('password'));
        $user->save();
        return $this->response->created();
    }
}

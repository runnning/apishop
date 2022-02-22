<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    /**
     * 登入
     *
     */
    public function login(LoginRequest $request): \Dingo\Api\Http\Response
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
              $this->response->errorUnauthorized('未授权!');
        }
        //检查用户状态
        $user=auth('api')->user();
        if($user?->is_locked===1){
             $this->response->errorUnauthorized('该用户已禁用!');
        }

        return $this->respondWithToken($token);
    }


    /**
     * 退出登录
     */
    public function logout(): \Dingo\Api\Http\Response
    {
        auth('api')->logout();
        return $this->response->noContent();
    }

    /**
     * 刷新token
     */
    public function refresh(): \Dingo\Api\Http\Response
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     *格式化返回
     *
     */
    protected function respondWithToken($token): \Dingo\Api\Http\Response
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}

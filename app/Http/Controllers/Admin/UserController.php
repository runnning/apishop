<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Static_;

class UserController extends BaseController
{

    /**
     * 用户列表
    */
    public function index(Request $request): \Dingo\Api\Http\Response
    {
        $name=$request->input('name');
        $email=$request->input('email');
        $users=User::when($name, static function ($query) use ($name){
            $query->where('name','like',"%$name%");
        })
            ->when($email, static function ( $query) use ($email){
                $query->where('name',$email);
            })
            ->paginate(10);
        return $this->response->paginator($users,new UserTransformer());
    }

    /**
     * 用户详情
    */
    public function show(User $user): \Dingo\Api\Http\Response
    {
        return $this->response->item($user,new UserTransformer());
    }

    /**
     * 禁用启用用户
    */
    public function lock(User $user): \Dingo\Api\Http\Response
    {
        $user->is_locked=$user->is_locked===0?1:0;
        $user->save();
        return $this->response->noContent();
    }

}

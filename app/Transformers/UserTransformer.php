<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\User;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{



    #[ArrayShape(['id' => "int", 'name' => "string", 'email' => "string", 'phone' => "mixed", 'avatar' => "mixed", 'is_locked' => "int", 'created_at' => "\Illuminate\Support\Carbon|null", 'updated_at' => "\Illuminate\Support\Carbon|null"])]
    public function transform(User $user): array
    {
        return [
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'avatar'=>$user->avatar,
            'avatar_url'=>oss_url($user->avatar),
            'is_locked'=>$user->is_locked,
            'created_at'=>$user->created_at->toDateTimeString(),
            'updated_at'=>$user->updated_at->toDateTimeString()
        ];
    }
}

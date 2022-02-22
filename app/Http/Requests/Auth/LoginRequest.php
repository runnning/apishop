<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use JetBrains\PhpStorm\ArrayShape;

class LoginRequest extends BaseRequest
{

    #[ArrayShape(['email' => "string", 'password' => "string"])]
    public function rules(): array
    {
        return [
            'email'=>'required|email',
            'password'=>'required|min:6|max:16'
        ];
    }
}

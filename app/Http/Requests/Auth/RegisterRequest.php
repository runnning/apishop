<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use JetBrains\PhpStorm\ArrayShape;

class RegisterRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['name' => "string", 'email' => "string", 'password' => "string"])]
    public function rules(): array
    {
        return [
            'name'=>'required|max:16',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|max:16|confirmed'
        ];
    }

    #[ArrayShape(['name.required' => "string",'name.max'=>"string"])]
    public function messages(): array
    {
        return[
            'name.required'=>'昵称不能为空',
            'name.max'=>'昵称不能超过16个字符'
        ];
    }
}

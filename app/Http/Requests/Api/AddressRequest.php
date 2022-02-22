<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class AddressRequest extends BaseRequest
{


    #[ArrayShape(['name' => "string", 'city_id' => "array", 'address' => "string", 'phone' => "string"])]
    public function rules(): array
    {
        return [
            'name'=>'required',
            'city_id'=>[
                'required',
                static function($attribute,$value,$fail){
                    $city=City::find($value);
                    if($city===null){
                        $fail('没有对应地址');
                    }
                    if(!in_array($city?->level, [3, 4], true)){
                        $fail('区域字段 必须是县级或者是乡镇');
                    }
                }
            ],
            'address'=>'required',
            'phone'=>'required|regex:/^1[3-9]\d{9}$/',
        ];
    }

    /**
     * 提示消息
    */
    #[ArrayShape(['name.required' => "string", 'city_id.required' => "string", 'phone.required' => "string", 'address.required' => "string"])]
    public function messages(): array
    {
        return [
            'name.required'=>'收货人 不能为空',
            'city_id.required'=>'地区 不能为空',
            'phone.required'=>'手机号 不能为空',
            'phone.regex'=>'手机号 格式不正确',
            'address.required'=>'详细收货地址 不能为空',
        ];
    }
}

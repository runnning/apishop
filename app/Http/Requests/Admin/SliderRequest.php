<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use JetBrains\PhpStorm\ArrayShape;

class SliderRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['title' => "string", 'img' => "string"])]
    public function rules(): array
    {
        return [
            'title'=>'required',
            'img'=>'required'
        ];
    }

    #[ArrayShape(['title.required' => "string", 'img.required' => "string"])]
    public function messages(): array
    {
        return [
            'title.required'=>'标题 不能为空',
            'img.required'=>'图片地址 必填'
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use JetBrains\PhpStorm\ArrayShape;

class GoodsRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['user_id' => "string", 'category_id' => "string",'title'=>'string', 'description' => "string", 'price' => "string", 'stock' => "string", 'cover' => "string", 'pics' => "string", 'details' => "string"])]
    public function rules(): array
    {
        return [
            'title'=>'required|max:255',
            'category_id'=>'required',
            'description'=>'required|max:255',
            'price'=>'required|min:0',
            'stock'=>'required|min:0',
            'cover'=>'required',
            'pics'=>'required|array',
            'details'=>'required',
        ];
    }
}

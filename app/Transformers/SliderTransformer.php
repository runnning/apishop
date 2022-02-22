<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\Slider;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;

class SliderTransformer extends TransformerAbstract
{

    #[ArrayShape(['id' => "int", 'title' => "string", 'url' => "null|string", 'img' => "string", 'seq' => "int", 'status' => "int", 'created_at' => "\Illuminate\Support\Carbon|null", 'updated_at' => "\Illuminate\Support\Carbon|null"])]
    public function transform(Slider $slider): array
    {
        return [
            'id'=>$slider->id,
            'title'=>$slider->title,
            'url'=>$slider->url,
            'img'=>$slider->img,
            'img_url'=>oss_url($slider->img),
            'seq'=>$slider->seq,
            'status'=>$slider->status,
            'created_at'=>$slider->created_at->toDateTimeString(),
            'updated_at'=>$slider->updated_at->toDateTimeString()
        ];
    }
}

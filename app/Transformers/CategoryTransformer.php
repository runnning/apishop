<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\Category;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{

    #[ArrayShape(['id' => "int", 'name' => "string"])]
    public function transform(Category $category): array
    {
        return [
            'id'=>$category->id,
            'name'=>$category->name,
        ];
    }
}

<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\Good;
use League\Fractal\TransformerAbstract;

class GoodTransformer extends TransformerAbstract
{
    protected $availableIncludes=['category','user','comments'];
    public function transform(Good $good): array
    {
        $pics_url=[];
        foreach ($good->pics as $pic){
            $pics_url[]=oss_url($pic);
        }
        return [
            'id'=>$good->id,
            'title'=>$good->title,
            'category_id'=>$good->category_id,
            //'category_name'=>Category::find($good->category_id)->name,
            'description'=>$good->description,
            'price'=>$good->price,
            'stock'=>$good->stock,
            'cover'=>$good->cover,
            'cover_url'=>oss_url($good->cover),
            'pics'=>$good->pics,
            'sales'=>$good->sales,
            'pics_url'=>$pics_url,
            'details'=>$good->description,
            'is_on'=>$good->is_on,
            'is_recommend'=>$good->is_recommend,
            'created_at'=>$good->created_at->toDateTimeString(),
            'updated_at'=>$good->updated_at->toDateTimeString()
        ];
    }

    /**
     * 额外的分类数据
    */
    public function includeCategory(Good $good): \League\Fractal\Resource\Item
    {
        return $this->item($good->category,new CategoryTransformer());
    }

    /**
     * 额外的用户数据
     */
    public function includeUser(Good $good): \League\Fractal\Resource\Item
    {
        return $this->item($good->user,new UserTransformer());
    }

    /**
     * 额外评价数据数据
    */
    public function includeComments(Good $good): \League\Fractal\Resource\Collection
    {
        return $this->collection($good->comments,new CommentTransformer());
    }
}

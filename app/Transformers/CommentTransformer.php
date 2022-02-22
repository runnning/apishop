<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\Comment;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    //可include的方法
    protected $availableIncludes=['user','goods'];

    public function transform(Comment $comment): array
    {
        $pics_url=[];

        if(is_array($comment->pics)){
            foreach ($comment->pics as $pic){
                $pics_url[]=oss_url($pic);
            }
        }

        return [
            'id'=>$comment->id,
            'content'=>$comment->content,
            'user_id'=>$comment->user_id,
            'goods_id'=>$comment->goods_id,
            'rate'=>$comment->rate,
            'reply'=>$comment->reply,
            'pics'=>$comment->pics,
            'pics_url'=>$pics_url,
            'created_at'=>$comment->created_at->toDateTimeString(),
            'updated_at'=>$comment->updated_at->toDateTimeString()
        ];
    }

    /**
     * 额外的用户数据
    */
    public function includeUser(Comment $comment): \League\Fractal\Resource\Item
    {
        return $this->item($comment->user,new UserTransformer());
    }

    /**
     * 额外的商品数据
    */
    public function includeGoods(Comment $comment): \League\Fractal\Resource\Item
    {
        return $this->item($comment->goods,new GoodTransformer());
    }
}

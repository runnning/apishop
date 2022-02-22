<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\Cart;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    protected $availableIncludes=['goods'];

    #[ArrayShape(['id' => "int|mixed", 'user_id' => "int|mixed", 'goods_id' => "int|mixed", 'num' => "int|mixed", 'is_checked' => "int|mixed"])]
    public function transform(Cart $cart): array
    {
        return [
            'id'=>$cart->id,
            'user_id'=>$cart->user_id,
            'goods_id'=>$cart->goods_id,
            'num'=>$cart->num,
            'is_checked'=>$cart->is_checked
        ];
    }

    /**
     * 额外的商品数据
    */
    public function includeGoods(Cart $cart): \League\Fractal\Resource\Item
    {
        return $this->item($cart->goods,new GoodTransformer());
    }
}

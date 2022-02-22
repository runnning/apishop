<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\OrderDetails;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;

class OrderDetailsTransformer extends TransformerAbstract
{
    protected $availableIncludes=['good'];
    #[ArrayShape(['id' => "int", 'order_id' => "int", 'email' => "int", 'price' => "int", 'num' => "int", 'created_at' => "\Illuminate\Support\Carbon|null", 'updated_at' => "\Illuminate\Support\Carbon|null"])] public function transform(OrderDetails $orderDetails): array
    {
        return [
            'id'=>$orderDetails->id,
            'order_id'=>$orderDetails->order_id,
            'email'=>$orderDetails->goods_id,
            'price'=>$orderDetails->price,
            'num'=>$orderDetails->num,
            'created_at'=>$orderDetails->created_at->toDateTimeString(),
            'updated_at'=>$orderDetails->updated_at->toDateTimeString()
        ];
    }


    /**
     * 额外的商品数据
    */

    public function includeGood(OrderDetails $orderDetails): \League\Fractal\Resource\Item
    {
        return $this->item($orderDetails->goods,new GoodTransformer());
    }
}

<?php

/**
 *User:ywn
 *Date:2021/12/13
 *Time:20:01
 */
namespace App\Transformers;
use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $availableIncludes=['user','orderDetails','goods'];

    public function transform(Order $order): array
    {
        return [
            'id'=>$order->id,
            'order_no'=>$order->order_no,
            'user_id'=>$order->user_id,
            'amount'=>$order->amount,
            'status'=>$order->status,
            'address_id'=>$order->address_id,
            'express_type'=>$order->express_type,
            'express_no'=>$order->express_no,
            'pay_time'=>$order->pay_time,
            'pay_type'=>$order->pay_type,
            'trade_no'=>$order->trade_no,
            'created_at'=>$order->created_at->toDateTimeString(),
            'updated_at'=>$order->updated_at->toDateTimeString()
        ];
    }

    /**
     * 额外的用户数据
    */
    public function includeUser(Order $order): \League\Fractal\Resource\Item
    {
        return $this->item($order->user,new UserTransformer());
    }

    /**
     * 额外的订单细节
    */
    public function includeOrderDetails(Order $order): \League\Fractal\Resource\Collection
    {
        return $this->collection($order->orderDetails,new OrderDetailsTransformer());
    }

    /**
     * 额外的商品信息
    */
    public function includeGoods(Order $order): \League\Fractal\Resource\Collection
    {
        return $this->collection($order->goods,new GoodTransformer());
    }
}

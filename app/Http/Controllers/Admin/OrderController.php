<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderPost;
use App\Http\Controllers\BaseController;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    /**
     * 订单列表
    */
    public function index(Request $request): \Dingo\Api\Http\Response
    {
        //查询条件
        $order_no=$request->input('order_no');
        $trade_no=$request->input('trade_no');
        $status=$request->input('status');

        $orders=Order::when($order_no, static function ($query) use ($order_no){
            $query->where('order_no',$order_no);
        })->when($trade_no, static function ($query) use ($trade_no){
            $query->where('trade_no',$trade_no);
        })->when($status, static function ($query) use ($status){
            $query->where('status',$status);
        })
        ->paginate();
        return $this->response->paginator($orders,new OrderTransformer());
    }

    /**
     * 订单详情
     */
    public function show(Order $order): \Dingo\Api\Http\Response
    {
        return $this->response->item($order,new OrderTransformer());
    }

    /**
     * 发货
     */
    public function post(Request $request,Order $order): \Dingo\Api\Http\Response
    {
        //验证提交的参数
        $request->validate([
            'express_type'=>'required|in:SF,YT,YD',
            'express_no'=>'required'
        ],[
           'express_type.required'=>'快递类型 必填',
           'express_type.in'=>'快递类型 只能是:SF YT YD',
           'express_no.required' =>'快递单号 必填'
        ]);

//        $order->express_type=$request->input('express_type');
//        $order->express_no=$request->input('express_no');
//        $order->status=3;//发货状态
//        $order->save();
//        //发货之后,邮件提醒
//        Mail::to($order->user)->queue(new OrderPost($order));

        //使用事件辅助函数分发
//        event(new OrderPost(
//            $order,
//            $request->input('express_type'),
//            $request->input('express_no')
//          )
//        );

        //是用事件静态方法分发
        OrderPost::dispatch(
            $order,
            $request->input('express_type'),
            $request->input('express_no')
        );
        return $this->response->noContent();
    }

}

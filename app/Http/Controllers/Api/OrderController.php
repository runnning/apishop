<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\BaseController;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Good;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Facades\Express\Facade\Express;

class OrderController extends BaseController
{

    /**
     * 订单列表
    */
    public function index(Request $request): \Dingo\Api\Http\Response
    {
        $status=$request->query('status');
        $title=$request->query('title');
        $orders=Order::where('user_id',auth('api')->id())
            ->when($status, static function ($query) use ($status){
                $query->where('status',$status);
            })
            ->when($title, static function ($query) use ($title){
                $query->whereHas('goods', static function ($query) use ($title){
                    $query->where('title','like',"%$title%");
                });
            })
            ->paginate(3);
        return $this->response->paginator($orders,new OrderTransformer());
    }

    /**
     * 预览订单
    */
    public function preview(): \Dingo\Api\Http\Response
    {
        //地址数据
        $address=Address::where('user_id',auth('api')->id())
            ->orderBy('is_default','desc')
            ->get();
        //购物车信息
        $carts=Cart::where([
            'user_id' => auth('api')->id(),
            'is_checked' => 1
        ])->with('goods:id,cover,title')
            ->get();

        //返回数据
        return $this->response->array([
            'address'=>$address,
            'carts'=>$carts
        ]);
    }

    /**
     * 提交订单
     * @throws \Throwable
     */
    public function store(Request $request): ?\Dingo\Api\Http\Response
    {
        //测试远程一对多
        //$order=Order::where('id',6)->with('goods')->get();

        $request->validate([
            'address_id'=>'required|exists:addresses,id'
        ],[
            'address_id.required'=>'收货地址 不能为空'
        ]);
        //处理插入数据
        $user_id=auth('api')->id();
        $order_no=date('YmdHis').random_int(100000,999999);
        $amount=0;

        $cartsQuery=Cart::where(['user_id' => $user_id,'is_checked' => 1])
            ->with('goods:id,price,stock,title');

        $carts=$cartsQuery->get();
        //要插入的订单详情数据
        $insertData=[];
        foreach ($carts as $key=>$cart){
            //如果有商品库存不足,提示重新选择
            if ($cart->goods->stock<$cart->num){
                 $this->response->errorBadRequest($cart->goods->title.'库存不足,请重新选择商品');
            }

            $insertData[]=[
                'goods_id'=>$cart->goods_id,
                'price'=>$cart->goods->price,
                'num'=>$cart->num
            ];
            $amount+=$cart->goods->price*$cart->num;
        }
        try {
            DB::beginTransaction();
            //生成订单
            $order=Order::create([
                'user_id' => $user_id,
                'order_no' => $order_no,
                'address_id' => $request->input('address_id'),
                'amount' => $amount
            ]);
            //生成订单详情
            $order->orderDetails()->createMany($insertData);

            //删除已经结算的购物车数据
            $cartsQuery->delete();

            //减去商品对应的库存量
            foreach ($carts as $cart){
                Good::where('id',$cart->goods_id)->decrement('stock',$cart->num);
            }

            DB::commit();

            return $this->response->created();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }
    }

    /**
     * 订单详情
    */
    public function show(Order $order): \Dingo\Api\Http\Response
    {
        return $this->response->item($order,new OrderTransformer());
    }

    /**
     * 物流查询
     * @throws \JsonException
     */
    public function express(Order $order): \Dingo\Api\Http\Response
    {
        if(!in_array($order->status,[3,4],true)){
            $this->response->errorBadRequest('订单状态异常!');
        }
        //容器注入
        $result=Express::track(OrderCode:$order->order_no,ShipperCode: $order->express_type,LogisticCode: $order->express_no);
        if(!is_array($result)){
            $this->response->errorBadRequest($result);
        }
        return $this->response->array($result);
    }

    /**
     * 确认收货
     * @throws \Throwable
     */
    public function confirm(Order $order): \Dingo\Api\Http\Response
    {
        if($order->status!==3){
            $this->response->errorBadRequest('订单状态异常!');
        }
        try {
            DB::beginTransaction();

            $order->status=4;
            $order->save();

            $orderDetails=$order->orderDetails;
            //增加订单加所有商品的销量
            foreach ($orderDetails as $orderDetail){
                //更新商品的销量
                Good::where('id',$orderDetail->goods_id)->increment('sales',$orderDetail->num);
            }
            DB::commit();
        }catch (\Throwable $throwable){
            DB::rollBack();
            throw $throwable;
        }

        return $this->response->noContent();
    }
}

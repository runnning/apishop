<?php

use Dingo\Api\Routing\Router;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\IndexController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GoodsController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PayController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CommentController;

$api=app(Router::class);
$params=[
    'middleware' => [
        'api.throttle',
        'bindings',//路由模型绑定注入
        'serializer:array' //减少transformer的包裹层
    ],
    'limit' => 60,
    'expires' => 1
];
$api->version('v1',$params, function ($api) {
    //首页数据
    $api->get('/index',[IndexController::class,'index']);
    //商品详情
    $api->get('goods/{good}',[GoodsController::class,'show']);
    //商品列表
    $api->get('goods',[GoodsController::class,'index']);

    /**
     * 回调
    */
    //支付宝支付成功后的回调
    $api->any('pay/notify/alipay',[PayController::class,'notifyAlipay']);
    //微信支付支付成功后回调
    $api->any('pay/notify/wechat',[PayController::class,'notifyWechat']);
    //需要登录的路由
    $api->group(['middleware'=>'api.auth'], static function ($api){
        /**
         * 个人中心
        */
        //用户详情
        $api->get('user',[UserController::class,'userInfo']);
        //更新用户信息
        $api->put('user',[UserController::class,'updateUserInfo']);
        //更新头像
        $api->patch('user/avatar',[UserController::class,'updateAvatar']);

        /**
         * 购物车
        */
        //购物车的资源路由
        $api->resource('carts',CartController::class,[
            'except'=>['show']
        ]);

        /**
         * 订单
        */
        //订单预览页
        $api->get('orders/preview',[OrderController::class,'preview']);
        //提交订单
        $api->post('orders',[OrderController::class,'store']);
        //订单详情
        $api->get('orders/{order}',[OrderController::class,'show']);
        //订单列表
        $api->get('orders',[OrderController::class,'index']);
        //物流查询
        $api->get('orders/{order}/express',[OrderController::class,'express']);
        //确认收货
        $api->patch('orders/{order}/confirm',[OrderController::class,'confirm']);
        //评论商品
        $api->post('orders/{order}/comment',[CommentController::class,'store']);

        /**
         * 支付
        */
        //获取支付信息
        $api->get('orders/{order}/pay',[PayController::class,'pay']);
        //轮询查询订单状态
        $api->get('orders/{order}/status',[PayController::class,'payStatus']);

        /**
         * 地址
        */
        //省市县数据
        $api->get('city',[CityController::class,'index']);

        //设置为默认地址
        $api->patch('address/{address}/default',[AddressController::class,'default']);
        //地址相关资源路由
        $api->resource('address',AddressController::class);
    });
});

<?php

use Dingo\Api\Routing\Router;
use App\Http\Controllers\TestController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GoodsController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\MenuController;

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
$api->version('v1',$params, static function ($api) {
    $api->group(['prefix'=>'admin'],static function($api){
        //需要登录的路由
        //check.permission
        $api->group(['middleware'=>['api.auth','check.permission']], static function ($api){
            /**
             * 用户管理
             */
            //禁用用户/启用用户
            $api->patch('users/{user}/lock',[UserController::class,'lock'])->name('users.lock');
            //用户管理资源路由
            $api->resource('users',UserController::class,[
                'only'=>['index','show']
            ]);

            /**
             * 分类管理
            */
            //分类禁用和启用
            $api->patch('category/{category}/status',[CategoryController::class,'status'])->name('category.status');
            //分类管理
            $api->resource('category',CategoryController::class,[
                'except'=>['destroy']
            ]);

            /**
             * 商品管理
            */
            //是否上架
            $api->patch('goods/{good}/on',[GoodsController::class,'isOn'])->name('goods.isOn');
            //是否推荐
            $api->patch('goods/{good}/recommend',[GoodsController::class,'isRecommend'])->name('goods.isRecommend');
            //商品管理资源路由
            $api->resource('goods',GoodsController::class,[
                'except'=>['destroy']
            ]);

            /**
             * 评价管理
            */
            //评价列表
            $api->get('comments',[CommentController::class,'index'])->name('comments.index');
            //评价详情
            $api->get('comments/{comment}',[CommentController::class,'show'])->name('comments.show');
            //回复评价
            $api->patch('comments/{comment}/reply',[CommentController::class,'reply'])->name('comments.reply');

            /**
             * 订单管理
            */
            //订单列表
            $api->get('orders',[OrderController::class,'index'])->name('orders.index');
            //订单详情
            $api->get('orders/{order}',[OrderController::class,'show'])->name('orders.show');
            //订单发货
            $api->patch('orders/{order}/post',[OrderController::class,'post'])->name('orders.post');

            /**
             * 轮播图管理
            */
            //排序
            $api->patch('sliders/{slider}/seq',[SliderController::class,'seq'])->name('sliders.seq');
            //轮播图禁用启用
            $api->patch('sliders/{slider}/status',[SliderController::class,'status'])->name('sliders.status');
            //轮播图管理资源路由
            $api->resource('sliders',SliderController::class);

            /**
             * 菜单管理
            */
            $api->get('menus',[MenuController::class,'index'])->name('menus.index');
        });
    });
});

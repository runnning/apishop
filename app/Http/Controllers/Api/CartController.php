<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Cart;
use App\Models\Good;
use App\Transformers\CartTransformer;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    /**
     * 购物车商品列表
     */
    public function index(): \Dingo\Api\Http\Response
    {
        $carts=Cart::where('user_id',auth('api')->id())->get();
        return $this->response->collection($carts,new CartTransformer());
    }

    /**
     * 加入购物车
     */
    public function store(Request $request): \Dingo\Api\Http\Response
    {
        $request->validate([
            'goods_id'=>'required|exists:goods,id',
            'num'=>[
                //数量不能超过商品的库存
                static function($attribute, $value, $fail) use($request){
                    $goods=Good::find($request['goods_id']);
                    if($value>$goods->stock){
                        $fail('数量 不能超过库存');
                    }
                }
            ]
        ],[
            'goods_id.required'=>'商品ID 不能为空',
            'goods_id.exists'=>'商品 不存在'
        ]);
        //查询购物车数据是否存在
        $cart=Cart::where([
            'user_id' => auth('api')->id(),
            'goods_id' => $request->input('goods_id')
        ])
            ->first();
        if(!empty($cart)){
            $cart->num=$request->input('num',1);
            $cart->save();
            return $this->response->noContent();
        }

        //添加购物车
        Cart::create([
            'user_id' => auth('api')->id(),
            'goods_id' => $request->input('goods_id'),
            'num'=>$request->input('num',1)
        ]);
        return $this->response->created();
    }


    /**
     * 数量增加减少
     */
    public function update(Request $request, Cart $cart): \Dingo\Api\Http\Response
    {
        $request->validate([
            'num'=>[
                'required',
                'gte:1',
                static function($attribute, $value, $fail)use($cart){
                    if ($value>$cart->goods->stock){
                        $fail('数量 不能超过最大库存');
                    }
                },
            ]
        ],[
            'num.required'=>'数量 不能为空',
            'num.gte'=>'数量 最少是1'
        ]);
        $cart->num=$request->input('num');
        $cart->save();
        return $this->response->noContent();
    }

    /**
     *移除购物车
     */
    public function destroy(Cart $cart): \Dingo\Api\Http\Response
    {
        $cart->delete();
        return $this->response->noContent();
    }
}

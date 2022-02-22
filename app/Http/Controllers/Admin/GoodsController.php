<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\GoodsRequest;
use App\Models\Category;
use App\Models\Good;
use App\Transformers\GoodTransformer;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{
    /**
     *商品列表
     *
     */
    public function index(Request $request): \Dingo\Api\Http\Response
    {
        $title=$request->query('title');
        $category_id=$request->query('category_id');
        $is_on=$request->query('is_on',false);
        $is_recommend=$request->query('is_recommend',false);
        $goods=Good::when($title, static function ($query) use ($title) {
            $query->where('title','like',"%$title%");
        })->when($category_id, static function ($query) use ($category_id) {
            $query->where('title',$category_id);
        })->when($is_on!==false, static function ($query) use ($is_on) {
            $query->where('is_on',$is_on);
        })->when($is_recommend!==false, static function ($query) use ($is_recommend) {
            $query->where('is_recommend',$is_recommend);
        })
        ->paginate(2);
       return $this->response->paginator($goods,new GoodTransformer());

    }

    /**
     *
     *添加商品
     */
    public function store(GoodsRequest $request)
    {
        //对分类进行下一行检查,只能使用3级分类，并且商品分类不能被禁用
        $category=Category::find($request->category_id);
        if(!$category){
            return $this->response->errorBadRequest('分类不存在');
        }
        if($category->status===0){
            return $this->response->errorBadRequest('分类被禁用');
        }
        if($category->level!==3){
            return $this->response->errorBadRequest('只能向3级分类添加商品');
        }

        $user_id=auth('api')->id();
        //追加user_id字段
//        $insertData=$request->all();
//        $insertData['user_id']=$user_id;
//        Good::create($insertData[));

        //追加user_id字段
        $request->offsetSet('user_id',$user_id);
        Good::create($request->all());
        return $this->response->created();
    }

    /**
     * 商品详情
     *
     */
    public function show(Good $good): \Dingo\Api\Http\Response
    {
        return $this->response->item($good,new GoodTransformer());
    }

    /**
     *更新商品
     *
     */
    public function update(GoodsRequest $request, Good $good)
    {
        //对分类进行下一行检查,只能使用3级分类，并且商品分类不能被禁用
        $category=Category::find($request->category_id);
        if(!$category){
            return $this->response->errorBadRequest('分类不存在');
        }
        if($category->status===0){
            return $this->response->errorBadRequest('分类被禁用');
        }
        if($category->level!==3){
            return $this->response->errorBadRequest('只能向3级分类添加商品');
        }
        $good->update($request->all());

        return $this->response->noContent();
    }

    /**
     *
     * 是否上架
    */
    public function isOn(Good $good): \Dingo\Api\Http\Response
    {
        $good->is_on=$good->is_on===0?1:0;
        $good->save();
        return $this->response->noContent();
    }

    /**
     *
     * 是否推荐
    */
    public function isRecommend(Good $good): \Dingo\Api\Http\Response
    {
        $good->is_recommend=$good->is_recommend===0?1:0;
        $good->save();
        return $this->response->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Good;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

class GoodsController extends BaseController
{

    /**
     * 商品列表
     * @throws \Exception
     */
    public function index(Request $request): \Dingo\Api\Http\Response
    {
        //搜索条件
        $title=$request->query('title');
        $category_id=$request->query('category_id');

        //排序
        $sales=$request->query('sales');
        $price=$request->query('price');
        $comments_count=$request->query('comments_count');
        //商品的分页数据
        $goods=Good::select('id','title','price','cover','category_id','sales','updated_at')
            ->where('is_on',1)
            ->when($title, static function ($query) use ($title){
                $query->where('title','like',"%$title%");
            })
            ->when($category_id, static function ($query) use ($category_id){
                $query->where('category_id',$category_id);
            })
            ->when($sales==='1', static function ($query){
                $query->orderBy('sales','desc');
            })
            ->when($price==='1', static function ($query){
                $query->orderBy('price','desc');
            })
            ->withCount('comments')
            ->when($comments_count==='1', static function ($query){
                $query->orderBy('comments_count','desc');
            })
            ->orderBy('updated_at','desc')
            ->simplePaginate(1)->appends([
                'title'=>$title,
                'category_id'=>$category_id,
                'sales'=>$sales,
                'price'=>$price,
                'comments_count'=>$comments_count
            ]);

        //推荐商品
        $recommend_goods=Good::select('id','title','price','cover')
            ->where(['is_on' => 1,'is_recommend' => 1])
            ->withCount('comments')
            ->inRandomOrder()
            ->take(10)
            ->get();
        //分类数据
        $categories=cache_category();

        return $this->response->array([
            'goods'=>$goods,
            'recommend_goods'=>$recommend_goods,
            'categories'=>$categories,
        ]);
    }

    /**
     * 商品详情
    */
    public function show($id): \Dingo\Api\Http\Response
    {
        //商品详情
        $goods=Good::where('id',$id)
            ->with([
                'comments.user'=> static function($query){
                    $query->select('id','name','avatar');
                }
            ])
            ->first()
            ?->append('pics_url');
        //相似的商品
        $like_goods=Good::where(['is_on' => 1,'category_id' => $goods->category_id])
            ->select('id','title','price','sales','cover')
            ->inRandomOrder()
            ->take(10)
            ->get();
        //返回数据
        return $this->response->array([
            'goods'=>$goods,
            'like_good'=>$like_goods
        ]);
    }
}


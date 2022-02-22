<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Models\Good;
use App\Models\Slider;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    /**
     * 首页数据
     * @throws \Exception
     */
    public function index(): \Dingo\Api\Http\Response
    {
        //轮播图数据
        $sliders=Slider::where('status',1)
            ->orderBy('seq')
            ->get();
        //分类数据
        $categories=cache_category();
        //推荐商品
        $goods=Good::where(['is_on' => 1,'is_recommend' => 1])
            ->take(20)
            ->get();

        return $this->response->array([
            'sliders'=>$sliders,
            'goods'=>$goods,
            'categories'=>$categories
        ]);
    }
}

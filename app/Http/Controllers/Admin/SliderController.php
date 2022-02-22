<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use App\Transformers\SliderTransformer;
use Illuminate\Http\Request;

class SliderController extends BaseController
{
    /**
     *列表
     */
    public function index(): \Dingo\Api\Http\Response
    {
        $sliders=Slider::paginate(2);
        return $this->response->paginator($sliders,new SliderTransformer());
    }

    /**
     *添加
     */
    public function store(SliderRequest $request): \Dingo\Api\Http\Response
    {
        //查询最大的seq
        $max_seq=Slider::max('seq')??0;
        $max_seq++;

        $request->offsetSet('seq',$max_seq);
        Slider::create($request->all());

        return $this->response->created();
    }

    /**
     * 详情
     */
    public function show(Slider $slider): \Dingo\Api\Http\Response
    {
        return $this->response->item($slider,new SliderTransformer());
    }

    /**
     *更新
     */
    public function update(SliderRequest $request, Slider $slider): \Dingo\Api\Http\Response
    {

        $slider->update($request->all());
        return $this->response->noContent();
    }

    /**
     *删除
     */
    public function destroy(Slider $slider): \Dingo\Api\Http\Response
    {
        $slider->delete();
        return $this->response->noContent();
    }

    /**
     * 排序
    */
    public function seq(Request $request, Slider $slider): \Dingo\Api\Http\Response
    {
        $slider->seq=$request->input('seq',1);
        $slider->save();
        return $this->response->noContent();
    }

    /**
     * 轮播图禁用和启用
    */
    public function status(Slider $slider): \Dingo\Api\Http\Response
    {
        $slider->status=$slider->status===1?0:1;
        $slider->save();
        return $this->response->noContent();
    }
}

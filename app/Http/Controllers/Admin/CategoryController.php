<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{

    /**
     * 分类列表
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $type=$request->input('type');

        if ($type==='all'){
            return cache_category_all();
        }

        return cache_category();

    }

    /**
     *添加分类 最大3级分类
     * @throws \Exception
     */
    public function store(Request $request)
    {
        //验证参数
        $insertData=$this->checkInput($request);
        if(!is_array($insertData)){
            return $insertData;
        }

        Category::create($insertData);

        return $this->response->created($insertData);
    }

    /**
     *
     *分类详情
     */
    public function show(Category $category): Category
    {
        return $category;
    }

    /**
     * 更新分类
     * @throws \Exception
     */
    public function update(Request $request, Category $category)
    {
        //验证参数
        $updateData=$this->checkInput($request);
        if(!is_array($updateData)){
            return $updateData;
        }

        $category->update($updateData);

        return $this->response->noContent();
    }

    /**
     *
     * 检查验证输入参数
    */
    protected function checkInput(Request $request){
        $request->validate([
            'name'=>'required|max:16',
        ],[
            'name.required'=>'分类名称不能为空'
        ]);

        //获取分组
        $group=$request->input('group','goods');
        //获取pid
        $pid=$request->input('pid',0);

        //计算level
        $level=$pid===0?1:(Category::find($pid)->level+1);

        //不能超过三级分类
        if($level>3) {
            return $this->response->errorBadRequest('不能超过三级分类');
        }
        return [
            'name'=>$request->input('name',''),
            'pid'=>$pid,
            'level'=>$level,
            'group'=>$group
        ];
    }

    /**
     *分类状态启用或禁用
     * @throws \Exception
     */
    public function status(Category $category): \Dingo\Api\Http\Response
    {
        $category->status=$category->status===1?0:1;

        $category->save();

        return $this->response->noContent();
    }
}

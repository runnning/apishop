<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        //删除缓存
        app()['cache']->forget('spatie.permission.cache');
        //添加权限
        $permissions=[
            //用户管理
            ['name'=>'users.index','cn_name'=>'用户列表','guard_name'=>'api'],
            ['name'=>'users.show','cn_name'=>'用户详情','guard_name'=>'api'],
            ['name'=>'users.lock','cn_name'=>'用户禁用启用','guard_name'=>'api'],

            //分类管理
            ['name'=>'category.status','cn_name'=>'分类禁用启用','guard_name'=>'api'],
            ['name'=>'category.index','cn_name'=>'分类列表','guard_name'=>'api'],
            ['name'=>'category.store','cn_name'=>'分类添加','guard_name'=>'api'],
            ['name'=>'category.show','cn_name'=>'分类详情','guard_name'=>'api'],
            ['name'=>'category.update','cn_name'=>'更新分类','guard_name'=>'api'],

            //商品管理
            ['name'=>'goods.isOn','cn_name'=>'商品是否上架','guard_name'=>'api'],
            ['name'=>'goods.isRecommend','cn_name'=>'商品是否推荐','guard_name'=>'api'],
            ['name'=>'goods.index','cn_name'=>'商品列表','guard_name'=>'api'],
            ['name'=>'goods.store','cn_name'=>'添加商品','guard_name'=>'api'],
            ['name'=>'goods.show','cn_name'=>'商品详情','guard_name'=>'api'],
            ['name'=>'goods.update','cn_name'=>'更新商品','guard_name'=>'api'],

            //评论管理
            ['name'=>'comments.index','cn_name'=>'评论列表','guard_name'=>'api'],
            ['name'=>'comments.show','cn_name'=>'评论详情','guard_name'=>'api'],
            ['name'=>'comments.reply','cn_name'=>'商家回复','guard_name'=>'api'],

            //订单管理
            ['name'=>'orders.index','cn_name'=>'订单列表','guard_name'=>'api'],
            ['name'=>'orders.show','cn_name'=>'订单详情','guard_name'=>'api'],
            ['name'=>'orders.post','cn_name'=>'订单发货','guard_name'=>'api'],

            //轮播i图管理
            ['name'=>'sliders.seq','cn_name'=>'轮播图排序','guard_name'=>'api'],
            ['name'=>'sliders.status','cn_name'=>'轮播图禁用启用','guard_name'=>'api'],
            ['name'=>'sliders.index','cn_name'=>'轮播图列表','guard_name'=>'api'],
            ['name'=>'sliders.store','cn_name'=>'轮播图添加','guard_name'=>'api'],
            ['name'=>'sliders.show','cn_name'=>'轮播图详情','guard_name'=>'api'],
            ['name'=>'sliders.update','cn_name'=>'更新轮播图','guard_name'=>'api'],
            ['name'=>'sliders.destroy','cn_name'=>'删除轮播图','guard_name'=>'api'],

            //菜单管理
            ['name'=>'menus.index','cn_name'=>'菜单列表','guard_name'=>'api'],
        ];
        foreach ($permissions as $permission){
            Permission::create($permission);
        }
        //添加角色
        $role=Role::create(['name'=>'super-admin','cn_name'=>'超级管理员','guard_name'=>'api']);
        //为角色授予权限
        $role->givePermissionTo(Permission::all());
    }
}

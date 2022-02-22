<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        //填充分类数据
        $categories=[
            [
                'name'=>'电子数码',
                'group'=>'goods',
                'pid'=>0,
                'level'=>1,
                'children'=>[
                    [
                        'name'=>'手机',
                        'group'=>'goods',
                        'level'=>2,
                        'children'=>[
                            [
                                'name'=>'华为',
                                'group'=>'goods',
                                'level'=>3,
                            ],
                            [
                                'name'=>'小米',
                                'group'=>'goods',
                                'level'=>3,
                            ],
                            [
                                'name'=>'iphone',
                                'group'=>'goods',
                                'level'=>3,
                            ]
                        ]
                    ],
                    [
                        'name'=>'电脑',
                        'group'=>'goods',
                        'level'=>2,
                        'children'=>[
                            [
                                'name'=>'联想',
                                'group'=>'goods',
                                'level'=>3,
                            ],
                            [
                                'name'=>'mac',
                                'group'=>'goods',
                                'level'=>3,
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name'=>'服装衣帽',
                'group'=>'goods',
                'pid'=>0,
                'level'=>1,
                'children'=>[
                    [
                        'name'=>'男装',
                        'group'=>'goods',
                        'level'=>2,
                        'children'=>[
                            [
                                'name'=>'阿迪',
                                'group'=>'goods',
                                'level'=>3,
                            ],
                            [
                                'name'=>'耐克',
                                'group'=>'goods',
                                'level'=>3,
                            ],
                            [
                                'name'=>'新百伦',
                                'group'=>'goods',
                                'level'=>3,
                            ]
                        ]
                    ]
                ]
            ],
        ];

        //写入到数据库
        foreach ($categories as $l1){
            $l1_tmp=$l1;
            unset($l1_tmp['children']);
            $l1_model=Category::create($l1_tmp);
            foreach ($l1['children'] as $l2){
                $l2_tmp=$l2;
                unset($l2_tmp['children']);
                $l2_tmp['pid']=$l1_model->id;
                $l2_model=Category::create($l2_tmp);
                $l2_model->children()->createMany($l2['children']);
            }
        }
        //清除分类缓存
        forget_cache_category();
    }
}

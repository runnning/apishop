<?php
/**
 * 辅助函数
 *User:ywn
 *Date:2022/1/25
 *Time:21:47
 */

use App\Models\Category;
use App\Models\City;


/**
 *分类层级
*/
if (!function_exists('categoryTree')){
    function categoryTree(bool|int $status=false,string $group='goods'): array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    {
        return Category::select(['id','pid','name','level','status'])
            //一级
            ->when($status!==false, static function ($query) use ($status){
                $query->where(['status' => $status]);
            })
            ->where(['pid'=>0,'group' => $group])->with([
                //二级
                'children'=>static function($query) use ($status){
                    $query->select(['id','pid','name','level','status'])
                        ->when($status!==false, static function ($query) use ($status){
                            $query->where(['status' => $status]);
                        });
                },
                //三级
                'children.children'=>static function($query) use ($status){
                    $query->select(['id','pid','name','level','status'])
                        ->when($status!==false, static function ($query) use ($status){
                        $query->where(['status' => $status]);
                    });
                }
            ])->get();
    }
}

/**
 * 缓存没被禁用的分类
*/
if(!function_exists('cache_category')){
    /**
     * @throws Exception
     */
    function cache_category(){
       return cache()->rememberForever('cache_category',static function (){
            return categoryTree(1);
        });
    }
}

/**
 * 缓存所有的分类
 */
if(!function_exists('cache_category_all')){
    /**
     * @throws Exception
     */
    function cache_category_all(){
       return cache()->rememberForever('cache_category_all',static function (){
            return categoryTree();
        });
    }
}

/**
 * 缓存没被禁用的菜单
 */
if(!function_exists('cache_category_menu')){
    /**
     * @throws Exception
     */
    function cache_category_menu(){
        return cache()->rememberForever('cache_category_menu',static function (){
            return categoryTree(1,'menu');
        });
    }
}

/**
 * 缓存所有的菜单
 */
if(!function_exists('cache_category_menu_all')){
    /**
     * @throws Exception
     */
    function cache_category_menu_all(){
        return cache()->rememberForever('cache_category_all_menu',static function (){
            return categoryTree(group: 'menu');
        });
    }
}
/**
 * 清空分类缓存
 */
if(!function_exists('forget_cache_category')){
    /**
     * @throws Exception
     */
    function forget_cache_category(){
        cache()->forget('cache_category');
        cache()->forget('cache_category_all');
        cache()->forget('cache_category_menu');
        cache()->forget('cache_category_menu_all');
   }
}

if (!function_exists('oss_url')){
    function oss_url(?string $key): string
    {
        //如果没有$key
        if(empty($key)){
            return '';
        }
        //如果$key包含http等，是一个完整的地址,直接返回
        if(str_contains($key, 'https://')
            ||str_contains($key, 'http://')
            ||str_contains($key,'data:image')){
            return  $key;
        }
        return config('filesystems.disks.oss.bucket_url').'/'.$key;
    }
}

/**
 * 城市相关缓存信息
 */
if (!function_exists('city_cache')){
    /**
     * @throws Exception
     */
    function city_cache(?int $pid=0)
    {
        return cache()->rememberForever('city_children'.$pid, static function ()use ($pid){
            return City::where('pid',$pid)->get()->keyBy('id');
        });
    }
}

/**
 * 通过3,4级 ID,查询完整的省市信息
 */
if (!function_exists('city_name')){
    /**
     * @throws Exception
     */
    function city_name(int $city_id=0): string
    {
        $city= City::where('id',$city_id)->with('parent.parent.parent')->first();
        $array=[
            $city['parent']['parent']['parent']['name']??'',
            $city['parent']['parent']['name']??'',
            $city['parent']['name']??'',
            $city['name']??'',
        ];
        return trim(implode(' ', $array));
    }
}

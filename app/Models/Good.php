<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Good
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Good newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Good newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Good query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id 创建者
 * @property string $title 标题
 * @property int $category_id 分类
 * @property string $description 描述
 * @property int $price 价格
 * @property int $stock 库存
 * @property string $cover 封面图
 * @property array $pics 小图集
 * @property int $is_on 是否上架 0不上架 1上架
 * @property int $is_recommend 是否推荐 0不推荐 1推荐
 * @property string $details 详情
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereIsOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereIsRecommend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good wherePics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereUserId($value)
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereTitle($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read string $cover_url
 * @method static \Database\Factories\GoodFactory factory(...$parameters)
 * @property int $sales 销量
 * @property-read \Illuminate\Support\Collection $pics_url
 * @method static \Illuminate\Database\Eloquent\Builder|Good whereSales($value)
 */
class Good extends Model
{
    use HasFactory;

    //可以批量赋值字段
    protected $fillable=[
        'title',
        'user_id',
        'category_id',
        'description',
        'price',
        'stock',
        'cover',
        'pics',
        'is_on',
        'is_recommend',
        'details'
    ];

    /**
     * 类型转换
     *
     * @var array
     */
    protected $casts = [
        'pics' => 'array',
    ];

    /**
     * 追加额外的属性
    */
    protected $appends=['cover_url'];

    /**
     * 访问器
     * cover url
    */
    public function getCoverUrlAttribute(): string
    {
        return oss_url($this->cover);
    }

    /**
     * pics oss url
    */
    public function getPicsUrlAttribute(): \Illuminate\Support\Collection
    {
        //使用集合处理每一项元素,返回处理后的新的集合
        return collect($this->pics)->map(static function (string $item){
            return oss_url($item);
        });
    }

    /**
     * 商品所属分类
    */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    /**
     * 商品所属用户
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * 商品所有的评价
    */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class,'goods_id','id');
    }
}

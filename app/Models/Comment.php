<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $user_id 评论的用户
 * @property int $goods_id 所属商品
 * @property int $rate 评论级别: 1好评 2中评 3差评
 * @property string $content 评论的内容
 * @property string|null $reply 商家回复
 * @property mixed|null $pics 多个评论图
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Good|null $goods
 * @property-read \App\Models\User|null $user
 * @property int $star 0-5星
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereStar($value)
 * @property int $order_id 评论的商品,所属的订单
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereOrderId($value)
 */
class Comment extends Model
{
    use HasFactory;

    //不允许批量赋值的字段
    protected $guarded=[];
    /**
     * 类型转换
     *
     * @var array
     */
    protected $casts = [
        'pics' => 'array',
    ];

    /**
     * 评论所属用户
    */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * 评论所属商品
    */
    public function goods(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Good::class,'goods_id','id');
    }
}

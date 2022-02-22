<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id 下单的用户
 * @property string $order_no 订单单号
 * @property int $amount 总金额 单位分
 * @property int $status 订单状态: 1下单 2支付 3发货 4收货 5作废
 * @property int $address_id 收货地址
 * @property string $express_type 快递类型: SF YT YD
 * @property string $express_no 快递单号
 * @property string|null $pay_time 支付时间
 * @property string|null $pay_type 支付类型: 支付宝 微信
 * @property string|null $trade_no 支付单号
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereExpressNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereExpressType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderDetails[] $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Good[] $goods
 * @property-read int|null $goods_count
 */
class Order extends Model
{
    use HasFactory;

    //可以批量赋值的字段
    protected $fillable=['user_id','order_no','amount','address_id','status','trade_no','pay_type','pay_time'];
    /**
     * 所属用户
    */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * 订单拥有的订单细节
     */
    public function orderDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetails::class,'order_id','id');
    }

    /**
     * 订单远程一对多,关联的商品
    */
    public function goods(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Good::class, //最终关联的模型
            OrderDetails::class,//中间模型
            'order_id',//中间模型和本模型关联的外键
            'id',//最终关联模型的外键
            'id',//本模型和中间模型关联的键
            'goods_id'//中间表和最终模型关联的键
        );
    }
}

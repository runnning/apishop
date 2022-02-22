<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\City
 *
 * @property int $id 城市ID
 * @property string $name 城市名
 * @property int $level 层级
 * @property int $pid 父级ID
 * @property-read \Illuminate\Database\Eloquent\Collection|City[] $children
 * @property-read int|null $children_count
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City wherePid($value)
 * @mixin \Eloquent
 * @property-read City|null $parent
 */
class City extends Model
{
    use HasFactory;

    //指定模型关联表名
    protected $table='city';

    /**
     * 城市子级
    */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(__CLASS__,'pid','id');
    }

    /**
     * 分级
    */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(__CLASS__,'pid','id');
    }
}

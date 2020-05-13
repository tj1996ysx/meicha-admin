<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class BeautyRequest extends Model
{
    use CrudTrait, DateScopeTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    const STATUS_PENDING = 'pending';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_INVALID = 'invalid';
    const STATUS_SUSPEND = 'suspend';

    const STATUSES = [
        'pending'   => '待处理',
        'contacted' => '已联系',
        'invalid'   => '无效信息',
        'suspend'   => '暂时无法联系',
    ];

    protected $table = 'beauty_requests';
    protected $guarded = ['id'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function beautyItems()
    {
        return $this->belongsToMany(
            BeautyItem::class,
            'beauty_request_items',
            'request_id',
            'item_id'
        )->withTimestamps();
    }

    public function shopper()
    {
        return $this->belongsTo(Shopper::class, 'shopper_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', static::STATUS_PENDING);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}

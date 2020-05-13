<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use CrudTrait, SoftDeletes, DateScopeTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    const STATUS_TO_PAY = 'to_pay';
    const STATUS_ORDER_SUCCESS = 'order_success';
    const STATUS_ORDER_FAIL = 'order_fail';
    const STATUS_PAID_SUCCESS = 'paid_success';
    const STATUS_PAID_FAIL = 'paid_fail';

    protected $table = 'orders';
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $order_no        = 'ORD'.date('Ymd').strtoupper(substr(uniqid(sha1(substr(microtime(), 2, 6))), -6, 6));
            $model->order_no = $order_no;
            $model->status   = self::STATUS_TO_PAY;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function shopper()
    {
        return $this->belongsTo(Shopper::class, 'shopper_id');
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePaid($query)
    {
        return $query->where('status', static::STATUS_PAID_SUCCESS);
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

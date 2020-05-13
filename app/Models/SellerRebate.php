<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SellerRebate extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'seller_rebates';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
//    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    const STATUS_UNSETTLED = 10;
    const STATUS_WITHDRAWING = 15;
    const STATUS_PAID = 20;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $seller_id = $model->seller_id;
            $seller    = Shopper::find($seller_id);
            if ($seller->parent_seller_id) {
                $model->parent_seller_id = $seller->parent_seller_id;
            } else {
                $model->parent_seller_id = $model->seller_id;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function seller()
    {
        return $this->belongsTo(Shopper::class, 'seller_id');
    }

    public function parent_seller()
    {
        return $this->belongsTo(Shopper::class, 'parent_seller_id');
    }

    public function shopper()
    {
        return $this->belongsTo(Shopper::class, 'shopper_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

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

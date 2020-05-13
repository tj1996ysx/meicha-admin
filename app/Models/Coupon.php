<?php

namespace App\Models;

use App\Events\CouponRedeemed;
use App\Models\Member;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;
    const STATUS_USED = 3;

    const STATUSES = [
        1 => '可用',
        2 => '不可用',
        3 => '已使用',
    ];

    protected $table = 'coupons';
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function redeem($shopper)
    {
        DB::beginTransaction();

        $membership = $this->couponBatch->membership;
        $member     = Member::findOrNew($shopper, $membership);

        $membership->issue($shopper);

        $this->status      = static::STATUS_USED;
        $this->shopper_id  = $shopper->id;
        $this->redeemed_at = Carbon::now()->toDateTimeString();
        $this->save();

        DB::commit();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function seller()
    {
        return $this->belongsTo(Shopper::class, 'seller_id', 'id');
    }

    public function couponBatch()
    {
        return $this->belongsTo(CouponBatch::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeValid($query)
    {
        return $query->where('status', static::STATUS_VALID);
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

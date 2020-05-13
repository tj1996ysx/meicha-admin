<?php

namespace App\Models;

use Backpack\Settings\app\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Member extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'members';
    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $membership       = Membership::find($model->membership_id);
            $prefix           = $membership->prefix.date('Ymd');
            $max_number       = Member::where('member_no', 'LIKE', $prefix.'%')->count();
            $model->member_no = $membership->prefix.date('Ymd').str_pad($max_number + 1, 6, '0', STR_PAD_LEFT);
        });
    }

    public static function findOrNew($shopper, $membership)
    {
        $member = static::ofShopper($shopper->id)->first();
        if (!$member) {
            $member = static::create([
                'membership_id' => $membership->id,
                'shopper_id'    => $shopper->id,
                'joined_at'     => Carbon::now()->toDateTimeString(),
            ]);
        }

        return $member;
    }

    public function appendPurchase($amount)
    {
        $this->increment('total_purchase', $amount);
        $this->increment('point_balance', $amount);

        $this->updateLevelInfo();
    }

    public function updateLevelInfo()
    {
        $purchase           = $this->total_purchase;
        $achievement_levels = Setting::get('achievement_level');
        $levels             = json_decode($achievement_levels, true);
        foreach ($levels as $level) {
            if (Arr::get($level, 'min_spending') <= $purchase
                && Arr::get($level, 'max_spending') >= $purchase) {
                $this->level = Arr::get($level, 'code');
                $this->save();

                return $this;
            }
        }

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

    public function shopper()
    {
        return $this->belongsTo(Shopper::class, 'shopper_id');
    }

    public function vouchers()
    {
        return $this->hasMany(ShopperVoucher::class, 'member_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOfShopper($query, $shopperId)
    {
        return $query->where('shopper_id', $shopperId);
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

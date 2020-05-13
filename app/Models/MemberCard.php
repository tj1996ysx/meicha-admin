<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class MemberCard extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'member_cards';
    protected $guarded = ['id'];

    const MAX_CARD_AMOUNT = 12;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $prefix         = 'CD';
            $max_number     = MemberCard::where('card_no', 'LIKE', $prefix.'%')->count();
            $model->card_no = $prefix.date('Ymd').str_pad($max_number + 1, 6, '0', STR_PAD_LEFT);
        });
    }

    public static function issue($membership, $shopper)
    {
        // issue card
        $card = static::create([
            'shopper_id'    => $shopper->id,
            'membership_id' => $membership->id,
            'member_id'     => $shopper->member->id,
            'purchased_at'  => Carbon::now()->toDateTimeString(),
            'expired_at'    => '2020-9-20' //TODO fixed to config
        ]);

        // issue vouchers
        $vouchers = $membership->vouchers;
        if ($vouchers->count() > 0) {
            $member_id = $card->member_id;
            foreach ($vouchers as $voucher) {
                $shopper->vouchers()->create([
                    'member_id'  => $member_id,
                    'card_id'    => $card->id,
                    'voucher_id' => $voucher->id,
                    'earned_at'  => Carbon::now()->toDateTimeString(),
                    'item_id'    => $voucher->item_id
                ]);
            }
        }
        return $card;
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

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function vouchers()
    {
        return $this->hasMany(ShopperVoucher::class, 'card_id');
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

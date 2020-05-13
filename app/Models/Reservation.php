<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Reservation extends Model
{
    use CrudTrait, DateScopeTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'shopper_vouchers';
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function cancel()
    {
        if ($this->status == ShopperVoucher::STATUS_RESERVED) {
            $this->status = ShopperVoucher::STATUS_UNUSED;
            $this->save();
            return true;
        }
        return false;
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

    public function item()
    {
        return $this->belongsTo(BeautyItem::class, 'item_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->whereNull('read_at')->where('status', ShopperVoucher::STATUS_RESERVED);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getVoucherNameAttribute()
    {
        if ($voucher = $this->voucher) {
            return $voucher->name;
        }
        return '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}

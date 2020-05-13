<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ShopperVoucher extends Model
{
    use CrudTrait;

    protected $guarded = ['id'];

    const STATUS_UNUSED = 'unused';
    const STATUS_RESERVED = 'reserved';
//    const STATUS_RESERVING = 'reserving';
    const STATUS_USED = 'used';
    const STATUS_EXPIRED = 'expired';

    protected $table = 'shopper_vouchers';

    protected $appends = ['status_label'];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $voucher_no        = 'VOU'.date('Ymd').strtoupper(substr(uniqid(sha1(substr(microtime(), 2, 6))), -6, 6));
            $model->voucher_no = $voucher_no;
            $model->status     = static::STATUS_UNUSED;
        });
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case static::STATUS_UNUSED:
                return '未使用';
            case static::STATUS_USED:
                return '已使用';
            case static::STATUS_RESERVED:
                return '已申请预约';
            case static::STATUS_EXPIRED:
                return '已过期';
            default:
                return '未知状态';
        }
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function item()
    {
        return $this->belongsTo(BeautyItem::class, 'item_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function shopper()
    {
        return $this->belongsTo(Shopper::class, 'shopper_id');
    }
}

<?php

namespace App\Observers;

use App\Models\CouponBatch;
use App\Models\Coupon;
use Illuminate\Support\Str;

class CouponBatchObserver
{
    /**
     * Handle the coupon batch "created" event.
     *
     * @param  \App\App\Models\CouponBatch  $couponBatch
     * @return void
     */
    public function created(CouponBatch $couponBatch)
    {
        $num = $couponBatch->end_code;
        for ($i=$couponBatch->start_code;$i<=$num;$i++) {
            $coupon_number = $i;
            $coupon = [
                'coupon_number' => $couponBatch->prefix.str_pad($coupon_number, 6, '0', STR_PAD_LEFT),
                'password' => Str::upper(Str::random(8)),
                'status' => '1',
                'coupon_batch_id' => $couponBatch->id,
            ];
            Coupon::create($coupon);
        }
    }

    /**
     * Handle the coupon batch "updated" event.
     *
     * @param  \App\App\Models\CouponBatch  $couponBatch
     * @return void
     */
    public function updated(CouponBatch $couponBatch)
    {
        //
    }

    /**
     * Handle the coupon batch "deleted" event.
     *
     * @param  \App\App\Models\CouponBatch  $couponBatch
     * @return void
     */
    public function deleted(CouponBatch $couponBatch)
    {
        //
    }

    /**
     * Handle the coupon batch "restored" event.
     *
     * @param  \App\App\Models\CouponBatch  $couponBatch
     * @return void
     */
    public function restored(CouponBatch $couponBatch)
    {
        //
    }

    /**
     * Handle the coupon batch "force deleted" event.
     *
     * @param  \App\App\Models\CouponBatch  $couponBatch
     * @return void
     */
    public function forceDeleted(CouponBatch $couponBatch)
    {
        //
    }
}

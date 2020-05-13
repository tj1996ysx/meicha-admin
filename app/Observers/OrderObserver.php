<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Order;

class OrderObserver
{

    /**
     * Handle the order "created" event.
     *
     * @param  \App\Models\Order  $order
     *
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Models\Order  $order
     *
     * @return void
     */
    public function updated(Order $order)
    {
        //when order paid successfully
        if ($order->isDirty('status')) {
            if ($order->getOriginal('status') == Order::STATUS_ORDER_SUCCESS
                && Order::STATUS_PAID_SUCCESS == $order->status) {
                $shopper    = $order->shopper;
                $membership = $order->membership;
                $member     = Member::findOrNew($shopper, $membership);
                $membership->issue($shopper, $order);
            }
        }
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     *
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Models\Order  $order
     *
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     *
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}

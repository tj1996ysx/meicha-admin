<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/8/30
 * Time: 5:58 PM
 */

namespace App\Listeners;

use App\Events\MembershipCardIssued;
use App\Models\SellerRebate;
use App\Models\Shopper;
use Carbon\Carbon;

class GiveRebateToSellerWhenCardIssued
{
    public function handle(MembershipCardIssued $cardIssued)
    {
        $order = $cardIssued->order;
        if (!$order) {
            return;
        }
        $card    = $cardIssued->card;
        $shopper = $card->shopper;

        if ($seller_id = $shopper->source_shopper_id) {
            $seller = Shopper::find($seller_id);
            if ($seller->parent_seller_id) {
                // give money to parent seller
            }

            $rebate = $shopper->rebase_rate;
            $amount = round($card->membership->price * $rebate, 2);
            SellerRebate::create([
                'seller_id'  => $seller_id,
                'amount'     => $amount,
                'shopper_id' => $card->shopper_id,
                'order_id'   => $card->order_id,
                'status'     => SellerRebate::STATUS_UNSETTLED,
                'rebased_at' => Carbon::now()->toDateTimeString(),
            ]);
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\MembershipCardIssued;
use App\Events\CouponUsed;
use App\Models\Shopper;
use App\Models\Voucher;
use Carbon\Carbon;

class IssueMemberVouchersWhenCardIssued
{
    public function handle(MembershipCardIssued $cardIssued)
    {
        $card       = $cardIssued->card;
        $shopper    = $card->shopper;
        $membership = $card->membership;
        $vouchers   = $membership->vouchers;
        if ($vouchers->count() > 0) {
            $member_id = $card->member_id;
            $now       = Carbon::now()->toDateTimeString();
            foreach ($vouchers as $voucher) {
                $shopper->vouchers()->create([
                    'member_id'  => $member_id,
                    'card_id'    => $card->id,
                    'voucher_id' => $voucher->id,
                    'earned_at'  => $now,
                    'item_id'    => $voucher->item_id
                ]);
            }
        }
    }
}

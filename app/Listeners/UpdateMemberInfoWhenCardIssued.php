<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/8/30
 * Time: 5:58 PM
 */

namespace App\Listeners;

use App\Events\MembershipCardIssued;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Arr;

class UpdateMemberInfoWhenCardIssued
{
    public function handle(MembershipCardIssued $cardIssued)
    {
        $member        = $cardIssued->card->member;
        $membership    = $cardIssued->card->membership;
        $points_earned = round($membership->price);

        $member->appendPurchase($points_earned);
    }
}

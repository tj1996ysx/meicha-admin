<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/8/30
 * Time: 5:55 PM
 */

namespace App\Events;

class MembershipCardIssued
{
    public $card;
    public $order;

    public function __construct($card, $order = null)
    {
        $this->card  = $card;
        $this->order = $order;
    }
}

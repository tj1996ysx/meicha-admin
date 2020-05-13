<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;

class CardController extends Controller
{
    public function cardInfo()
    {
        $data = Membership::getCartInfo();
        return response()->json(['data' => $data]);
    }

    public function myCards()
    {
        $shopper = auth('api')->user();

        $cards = $shopper->cards()->withCount(['vouchers' => function ($query) {
            $query->whereNull('used_at');
        }])->get();

        $card_list = [];
        foreach ($cards as $card) {
            $card_list[] = [
                'card_id' => $card->id,
                'card_no' => $card->card_no,
                'vouchers' => $card->vouchers_count,
                'expired_at' => $card->expired_at
            ];
        }

        return response()->json($card_list);
    }
}

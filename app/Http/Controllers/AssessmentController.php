<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssessmentRequest;
use App\Models\Assessment;
use App\Models\ShopperVoucher;
use Illuminate\Http\Request;

class AssessmentController extends ApiController
{
    public function submit(AssessmentRequest $request)
    {
        $shopper_id      = auth('api')->user()->id;
        $voucher_no      = $request->voucher_no;
        $shopper_voucher = ShopperVoucher::where('voucher_no', $voucher_no)->first();

        if (!$shopper_voucher || ($shopper_voucher->shopper_id != $shopper_id)) {
            return $this->fail('找不到相应的权益券');
        }

        $shopper_voucher_id = $shopper_voucher->id;

        if (Assessment::ofVoucher($shopper_voucher_id)->count()) {
            return $this->fail('您已经评价过了');
        }

        Assessment::create([
            'shopper_id'         => $shopper_id,
            'shopper_voucher_id' => $shopper_voucher_id,
            'hospital_id'        => $shopper_voucher->hospital_id,
            'rate'               => (int)$request->rate,
            'comment'            => (string)$request->comment,
        ]);

        return $this->success([
            'message' => '已经收到您的评价, 谢谢您的支持!'
        ]);
    }
}

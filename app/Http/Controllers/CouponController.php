<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRedeemRequest;
use App\Models\Coupon;

class CouponController extends ApiController
{
    public function redeem(CouponRedeemRequest $request)
    {
        $coupon = Coupon::valid()->where('password', $request->password)->first();
        if (!$coupon) {
            return $this->fail('兑换券无效');
        }
        $shopper = auth('api')->user();
        $coupon->redeem($shopper);

        return $this->success(['message' => '兑换成功']);
    }
}

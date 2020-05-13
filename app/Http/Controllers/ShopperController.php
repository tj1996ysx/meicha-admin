<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;

class ShopperController extends Controller
{
    /**
     * update seller bank info
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBankInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seller_name' => 'required|min:2|max:10',
            'bank_name' => 'sometimes|min:2|max:50',
            'bank_card_no' => 'required|min:10|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => '无效的银行卡号'], 422);
        }

        $shopper = auth('api')->user();
        $shopper->name = $request->input('seller_name');
        $shopper->bank_name = $request->input('bank_name');
        $shopper->bank_card_no = $request->input('bank_card_no');
        $shopper->save();

        return response()->json(['message' => '银行卡信息更新成功', 'user' => $shopper]);
    }
}

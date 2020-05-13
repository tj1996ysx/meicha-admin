<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\MemberCard;
use App\Models\ShopperVoucher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * set hospital info to reserve service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doReserve(Request $request)
    {
        $shopper_name = $request->input('name');
        $mobile = $request->input('mobile');
        $voucher_no = $request->input('voucher_no');
        $hospital_id = $request->input('hospital_id');

        $shopper = auth('api')->user();

        // 是否有权益券在预约中
        $reserved = ShopperVoucher::where('status', ShopperVoucher::STATUS_RESERVED)
            ->where('shopper_id', $shopper->id)
            ->count();
        if ($reserved) {
            return response()->json(['message' => '您已经有一张权益券正在预约中，请耐心等候，我们的客服马上联系您。']);
        }

        try {
            $voucher = ShopperVoucher::where('status', ShopperVoucher::STATUS_UNUSED)
                ->where('shopper_id', $shopper->id)
                ->where('voucher_no', $voucher_no)
                ->firstOrFail();
            $voucher->shopper_name = $shopper_name;
            $voucher->mobile = $mobile ?? $shopper->mobile;
            $voucher->hospital_id = $hospital_id;
            $voucher->reserved_at = Carbon::now()->toDateTimeString();
            $voucher->status = ShopperVoucher::STATUS_RESERVED;
            $voucher->save();
            return response()->json(['message' => '系统正在为您预约时间, 请耐心等候, 我们的客服人员会尽快联系您~']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => '券已使用或已预约'], 403);
        }
    }

    /**
     * get shopper voucher list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shopperVouchers(Request $request)
    {
        $shopper = auth('api')->user();
        $shopper_vouchers = [];
        $card_id = $request->input('cardid', 0);
        if ($card_id) {
            $card = MemberCard::with('vouchers')->find($card_id);
            if ($card) {
                $shopper_vouchers = $card->vouchers;
            }
        } else {
            $shopper_vouchers = $shopper->vouchers()->with('voucher')->orderBy('earned_at', 'ASC')->get();
        }

        $vouchers = [];
        foreach ($shopper_vouchers as $shopper_voucher) {
            $voucher_info = $shopper_voucher->voucher;
            $vouchers[] = [
                'voucher_no' => $shopper_voucher->voucher_no,
                'shopper_name' => $shopper_voucher->shopper_name,
                'mobile' => $shopper_voucher->mobile,
                'item_name' => $shopper_voucher->item->name,
                'status' => $shopper_voucher->status,
                'status_label' => $shopper_voucher->status_label,
                'voucher_name' => $voucher_info->name,
                'voucher_image' => url($voucher_info->image_url),
                'voucher_desc' => $voucher_info->description,
                'hospital' => $shopper_voucher->hospital_id,
            ];
        }

        $hospitals = Hospital::getHospitalList();

        return response()->json(['vouchers' => $vouchers, 'hospitals' => $hospitals]);
    }

    /**
     * set voucher used by shopper
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function useVoucher(Request $request)
    {
        $shopper = auth('api')->user();
        $voucher_no = $request->input('voucher_no');
        $hospital_id = $request->input('hospital_id');
        $voucher = ShopperVoucher::where('shopper_id', $shopper->id)
            ->where('voucher_no', $voucher_no)
            ->first();
        if ($voucher) {
            if ($voucher->status == ShopperVoucher::STATUS_USED) {
                $return = [
                    'status' => -2,
                    'message' => '券不能重复核销'
                ];
            } else {
                $voucher->status = ShopperVoucher::STATUS_USED;
                $voucher->used_at = now()->toDateTimeString();
                $voucher->hospital_id = $hospital_id;
                $voucher->save();
                $return = [
                    'status' => 1,
                    'message' => '成功核销'
                ];
            }
        } else {
            $return = [
                'status' => -1,
                'message' => '抱歉，所使用的券不存在'
            ];
        }

        return response()->json($return);
    }
}

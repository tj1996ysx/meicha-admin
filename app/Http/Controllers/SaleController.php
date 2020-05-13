<?php

namespace App\Http\Controllers;

use App\Models\SellerRebate;
use App\Models\SellerWithdraw;
use App\Models\Shopper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * shoppers by seller recommend
     * @param $request
     * @return JsonResponse
     */
    public function myShoppers(Request $request)
    {
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');

        $seller = auth('api')->user();

        $query = SellerRebate::with('shopper');

        if ( $seller->seller_level == 1) {
            $query->where('parent_seller_id', $seller->id);
        } else {
            $query->where('seller_id', $seller->id);
        }

        if ($start_date && $end_date) {
            $query->whereBetween('rebased_at', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }

        $query->orderBy('rebased_at', 'desc');

        $rebase_list = $query->paginate(20);

        return response()->json($rebase_list);
    }

    /**
     * seller withdraw history list
     * @param Request $request
     * @return JsonResponse
     */
    public function myWithdraws(Request $request)
    {
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');

        $seller = auth('api')->user();
        $query = SellerWithdraw::where('seller_id', $seller->id)
            ->orderBy('requested_at', 'desc');
        if ($start_date && $end_date) {
            $query->whereBetween('requested_at', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }
        $seller_withdraws = $query->paginate(20);
        $status_refer = [
            SellerWithdraw::STATUS_REQUESTED => '处理中',
            SellerWithdraw::STATUS_PAID => '已提现',
        ];

        return response()->json(['list' => $seller_withdraws, 'status_refer' => $status_refer]);
    }

    /**
     * get my sales data
     */
    public function mySales()
    {
        $seller = auth('api')->user();

        if ($seller->seller_level == 1) {
            $today_qty = $seller->parent_rebases()
                ->where('rebased_at', 'like', Carbon::now()->toDateString().'%')
                ->count();
            $total_qty = $seller->parent_rebases()->count();
            $money_total = $seller->parent_rebases()->sum('amount');
            $money_balance = $seller->parent_rebases()->where('status', SellerRebate::STATUS_UNSETTLED)->sum('amount');
        } else {
            $today_qty = $seller->rebases()
                ->where('rebased_at', 'like', Carbon::now()->toDateString().'%')
                ->count();
            $total_qty = $seller->rebases()->count();
            $money_total = 0;
            $money_balance = 0;
        }

        $data = [
            'today_qty' => $today_qty,
            'total_qty' => $total_qty,
            'money_total' => $money_total,
            'money_balance' => $money_balance,
        ];

        return response()->json($data);
    }

    /**
     * send withdraw request
     * @param Request $request
     * @return JsonResponse
     */
    public function withdraw(Request $request)
    {
        $seller = auth('api')->user();
        $amount = $request->input('amount', 0);
        $rebases = SellerRebate::where('seller_id', $seller->id)
            ->where('status', SellerRebate::STATUS_UNSETTLED)
            ->get();
        $base_amount = $rebases->sum('amount');
        if ($amount != $base_amount || $amount <= 0) {
            return response()->json(['message' => '提现金额不正确，当前不支持部分提现'], 422);
        } else {
            $withdraw = SellerWithdraw::create([
                'seller_id' => $seller->id,
                'amount' => $amount,
                'requested_at' => Carbon::now()->toDateTimeString(),
                'status' => SellerWithdraw::STATUS_REQUESTED
            ]);

            $rebase_ids = $rebases->pluck('id')->toArray();
            SellerRebate::whereIn('id', $rebase_ids)->update([
                'status' => SellerRebate::STATUS_WITHDRAWING,
                'withdraw_id' => $withdraw->id
            ]);

            return response()->json(['message' => '提现请求成功，请耐心等待汇款']);
        }
    }

    /**
     * Get sellers belongs to me
     */
    public function mySellers()
    {
        $user = auth('api')->user();

        $sellers = Shopper::where('role', Shopper::ROLE_SELLER)
            ->where('parent_seller_id', $user->id)
            ->paginate(20);

        return response()->json($sellers);
    }

    /**
     * Get sales list of a level 2 seller
     *
     * @param $seller_id
     */
    public function mySellerSales($seller_id)
    {
        // get sales records
        $parent_seller = auth('api')->user();
        $query = SellerRebate::with('shopper')
            ->where('seller_id', $seller_id)
            ->where('parent_seller_id', $parent_seller->id)
            ->orderBy('rebased_at', 'desc');

        $start_date = request('start_date');
        $end_date = request('end_date');

        if ($start_date && $end_date) {
            $query->whereBetween('rebased_at', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }
        $rebase_list = $query->paginate(20);

        return response()->json($rebase_list);
    }
}

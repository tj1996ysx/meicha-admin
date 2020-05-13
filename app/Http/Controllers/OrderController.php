<?php

namespace App\Http\Controllers;

use App\Models\MemberCard;
use App\Models\Membership;
use App\Models\Order;
use App\Models\SellerRebate;
use Carbon\Carbon;
use EasyWeChat\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OrderController extends Controller
{
    /**
     *
     * request for an order pay, not real pay
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function createOrder(Request $request)
    {
        $shopper = auth('api')->user();
        $membership_id = $request->input('cardid', 1);
        $quantity = $request->input('qty', 1);
        $membership = $this->getMembershipToPurchase($membership_id);

        if ($shopper->cards()->count() + $quantity > MemberCard::MAX_CARD_AMOUNT) {
            return response()->json(['message' => '抱歉，您的购买已超出限购']);
        }

        //create order record
        $order = $this->createOrderRecord($shopper, $membership, $quantity);

        //update order after call wechat API
        $pre_pay_result = $this->orderToWechat($shopper, $order);
        $status = Arr::get($pre_pay_result, 'result_code');
        if ('SUCCESS' == $status) {
            $prepay_id = Arr::get($pre_pay_result, 'prepay_id');
            $order->status = Order::STATUS_ORDER_SUCCESS;
            $order->prepay_id = $prepay_id;
            $order->prepay_result = json_encode($pre_pay_result);
            $order->save();
            $return = [
                'appId' => config('wechat.mini_program.default.app_id'),
                'timeStamp' => time(),
                'nonceStr' => uniqid(bin2hex(random_bytes(6))),
                'package' => 'prepay_id=' . $prepay_id,
                'signType' => 'MD5'
            ];
            $return['paySign'] = $this->doSign($return);
            return response()->json($return);
        } else {
            $order->status = Order::STATUS_ORDER_FAIL;
            $order->prepay_result = json_encode($pre_pay_result);
            $order->save();

            return response()->json(['message' => '发起支付失败'], 422);
        }
    }

    /**
     * notify us that the order is paid
     * @return mixed
     */
    public function paidNotify()
    {
        $config = config('wechat.payment.default');
        $payment_app = Factory::payment($config);
        $response = $payment_app->handlePaidNotify(function ($message, $fail) {
            //find our order
            $order_no = Arr::get($message, 'out_trade_no');
            $order = Order::where('order_no', $order_no)->first();
            if (!$order || $order->paid_at) {
                return true;
            }

            //签名验证？

            if (round($order->total_paid*100) != Arr::get($message, 'total_fee')) {
                return $fail('支付金额不对，假通知？');
            }

            if (Arr::get($message, 'return_code') === 'SUCCESS') {
                if (Arr::get($message, 'result_code') === 'SUCCESS') {
                    $order->paid_at = date('Y-m-d H:i:s', strtotime(Arr::get($message, 'time_end')));
                    $order->status = Order::STATUS_PAID_SUCCESS;
                } else {
                    $order->status = Order::STATUS_PAID_FAIL;
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->paid_result = json_encode($message);
            $order->save();

            return true;
        });
        return $response;
    }

    private function createOrderRecord($shopper, $membership, $quantity)
    {
        $total_paid = round($membership->price * $quantity, 2);
        $order = Order::create([
            'shopper_id' => $shopper->id,
            'total_paid' => $total_paid,
            'membership_id' => $membership->id,
            'quantity' => $quantity,
            'request_at' => Carbon::now()->toDateTimeString()
        ]);

        return $order;
    }

    /**
     * get the card to pay
     * @param int $membershipId
     * @return Membership
     */
    private function getMembershipToPurchase($membershipId = 1)
    {
        try {
            $membership = Membership::findOrFail($membershipId);
        } catch (ModelNotFoundException $e) {
            $membership = new Membership();
            $membership->price = 199.00;
            $membership->name = '红人卡';
            $membership->prefix = 'MCC';
            $membership->save();
        }

        return $membership;
    }

    private function orderToWechat($shopper, $order)
    {
        $config = config('wechat.payment.default');
        $payment_app = Factory::payment($config);

        try {
            $params = [
                'body' => '美查-红人卡',
                'out_trade_no' => $order->order_no,
                'total_fee' => (int) round($order->total_paid * 100),
                'spbill_create_ip' => request()->server('SERVER_ADDR'),
                'notify_url' => url('api/payment/paid_notify', [], true),
                'trade_type' => 'JSAPI',
                'openid' => $shopper->open_id
            ];
            $result = $payment_app->order->unify($params);

            return $result;
        } catch (\Exception $e) {
            \Log::error('pre-order to wechat error for order - ' . $order->id . ' : ' . $e->getMessage());
            return null;
        }
    }

    private function doSign($params)
    {
        $base = array_filter($params);
        ksort($base);
        $return_str = http_build_query($base);
        $key = config('wechat.payment.default.key');
        $temp_sign = urldecode($return_str . '&key=' . $key);
        $sign = strtoupper(md5($temp_sign));

        return $sign;
    }
}

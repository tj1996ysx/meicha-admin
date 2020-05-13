<?php

namespace App\Console\Commands;

use App\Models\Order;
use EasyWeChat\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class UpdateOrderByPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:update_by_pay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order by query pay result';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $order = Order::find(1);
        $order->status = Order::STATUS_PAID_SUCCESS;
        $order->paid_at = date('Y-m-d H:i:s');
        $order->save();

        return;

        //---


        $config = config('wechat.payment.default');
        $payment_app = Factory::payment($config);
        $orders = Order::where('status', Order::STATUS_ORDER_SUCCESS)->get();

        foreach ($orders as $order) {
            try {
                $result = $payment_app->order->queryByOutTradeNumber($order->order_no);

                if ('SUCCESS' == Arr::get($result, 'result_code')) {
                    $trade_state = Arr::get($result, 'trade_state');
                    if ($trade_state == 'SUCCESS') {
                        $order->status = Order::STATUS_PAID_SUCCESS;
                        $order->paid_at = date('Y-m-d H:i:s', strtotime(Arr::get($result, 'time_end')));
                        $order->save();
                    } else {
                        $order->paid_result = json_encode([
                            'state' => $trade_state,
                            'err_code' => Arr::get($result, 'err_code'),
                            'err_code_des' => Arr::get($result, 'err_code_des'),
                        ]);
                        $order->save();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('query order failed: '.$order->id.' - '.$e->getMessage());
            }
        }
    }
}

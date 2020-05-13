<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\CouponBatch;
use App\Observers\OrderObserver;
use App\Observers\CouponBatchObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Order::observe(OrderObserver::class);
        CouponBatch::observe(CouponBatchObserver::class);
    }
}

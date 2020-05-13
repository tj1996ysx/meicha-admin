<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('coupon_number')->comment('优惠券号码');
            $table->string('password')->comment('密码');
            $table->integer('seller_id')->nullable()->comment('销售人员id');
            $table->integer('shopper_id')->default(0)->comment('使用人员id');
            $table->integer('coupon_batch_id')->nullable()->comment('兑换券批次id');
            $table->decimal('amount', 8, 2)->nullable()->comment('费用');
            $table->tinyInteger('status')->comment('分配状态');
            $table->text('comment')->nullable()->comment('备注');
            $table->dateTime('redeemed_at')->nullable()->comment('兑换时间');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prefix')->comment('前缀');
            $table->integer('start_code')->comment('起始码');
            $table->integer('end_code')->comment('结束码');
            $table->integer('membership_id')->comment('红人卡id');
            $table->text('comment')->nullable()->comment('备注');
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
        Schema::dropIfExists('coupon_batches');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerRebatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_rebates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seller_id');
            $table->decimal('amount');
            $table->integer('shopper_id');
            $table->integer('order_id');
            $table->string('status', 8)->default('10');
            $table->dateTime('rebased_at');
            $table->dateTime('paid_at')->nullable();
            $table->integer('withdraw_id')->nullable();
            $table->unsignedBigInteger('paid_by')->nullable()->comment('操作者 user id');
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
        Schema::dropIfExists('seller_rebates');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_withdraws', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seller_id');
            $table->decimal('amount');
            $table->dateTime('requested_at');
            $table->string('status', 20);
            $table->dateTime('paid_at')->nullable();
            $table->integer('pay_by')->nullable();
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
        Schema::dropIfExists('seller_withdraws');
    }
}

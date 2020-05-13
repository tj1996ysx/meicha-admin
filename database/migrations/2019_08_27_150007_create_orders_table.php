<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no')->index();
            $table->integer('shopper_id');
            $table->decimal('total_paid');
            $table->integer('quantity')->default(1);
            $table->integer('membership_id')->default(0);
            $table->dateTime('request_at');
            $table->dateTime('paid_at')->nullable();
            $table->string('status', 50)->defualt(\App\Models\Order::STATUS_TO_PAY);
            $table->string('prepay_id')->nullable();
            $table->text('prepay_result')->nullable();
            $table->text('paid_result')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

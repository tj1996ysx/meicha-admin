<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopperVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopper_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no')->index();
            $table->integer('voucher_id');
            $table->integer('member_id');
            $table->integer('card_id');
            $table->integer('shopper_id');
            $table->string('shopper_name')->nullable();
            $table->string('mobile')->nullable();
            $table->dateTime('earned_at');
            $table->dateTime('reserved_at')->nullable();
            $table->dateTime('agreed_at')->nullable();
            $table->dateTime('used_at')->nullable();
            $table->dateTime('expire_at')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->integer('read_by')->default(0);
            $table->integer('item_id')->default(0);
            $table->integer('hospital_id')->default(0);
            $table->string('status')->default(\App\Models\ShopperVoucher::STATUS_UNUSED);
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('shopper_vouchers');
    }
}

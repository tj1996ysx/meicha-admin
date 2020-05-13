<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique()->comment('邀请码');
            $table->string('type')->comment('邀请类型: 1: 一级分销; 2: 二级分销');
            $table->unsignedBigInteger('from_shopper_id')->comment('邀请者');
            $table->unsignedBigInteger('shopper_id')->nullable()->comment('被邀请者');
            $table->decimal('rebate')->comment('返点: 0.01 ~ 0.99')->default(0.5);
            $table->string('name')->nullable()->comment('真实姓名');
            $table->integer('status')->comment('状态: 0: 可用; 1: 接受; 2: 拒绝; 3: 过期');
            $table->dateTime('accept_at')->nullable()->comment('接受时间');
            $table->dateTime('reject_at')->nullable()->comment('拒绝时间');
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
        Schema::dropIfExists('invitations');
    }
}

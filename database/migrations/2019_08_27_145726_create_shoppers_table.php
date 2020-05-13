<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoppers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('nickname')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('mobile')->nullable();
            $table->string('gender')->default('unknown');
            $table->string('language')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('open_id')->nullable()->comment('wechat user ID');
            $table->string('session_key')->nullable()->comment('wechat login session key');
            $table->string('union_id')->nullable()->comment('wechat user platform ID');
            $table->string('role')->default('shopper');
            $table->decimal('rebase_rate')->default(0.5);
            $table->string('remark')->nullable();
            $table->dateTime('registered_at')->nullable();
            $table->integer('source_shopper_id')->default(0);
            $table->string('refer_code')->nullable();
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
        Schema::dropIfExists('shoppers');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeautyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beauty_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shopper_id');
            $table->string('status')->default('pending');
            $table->string('mobile')->nullable();
            $table->string('project')->nullable();
            $table->string('budget')->nullable();
            $table->string('city')->nullable();
            $table->text('photo')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('beauty_requests');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSellerLevelToShoppersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shoppers', function (Blueprint $table) {
            $table->integer('seller_level')->default(0)->comment('销售等级: 1: 一级分销; 2: 二级分销;');
            $table->integer('parent_seller_id')->nullable()->comment('上级销售人员');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shoppers', function (Blueprint $table) {
            $table->dropColumn('seller_level');
            $table->dropColumn('parent_seller_id');
        });
    }
}

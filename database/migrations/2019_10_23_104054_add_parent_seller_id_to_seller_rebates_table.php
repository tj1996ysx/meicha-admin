<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentSellerIdToSellerRebatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller_rebates', function (Blueprint $table) {
            $table->integer('parent_seller_id')->nullable()->comment('上级代理');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_rebates', function (Blueprint $table) {
            $table->dropColumn('parent_seller_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToHospitalsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->string('latitude')->nullable()->comment('纬度');
            $table->string('longitude')->nullable()->comment('经度');
            $table->string('desc')->nullable()->comment('简介图片');
            $table->text('environments')->nullable()->comment('环境图片');
            $table->text('experts')->nullable()->comment('专家图片');
            $table->string('map')->nullable()->comment('地址图片');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'latitude',
                    'longitude',
                    'desc',
                    'environments',
                    'experts'
                ]
            );
        });
    }
}

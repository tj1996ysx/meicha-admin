<?php

use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run()
    {
        $url = env('APP_URL');
        \App\Models\Membership::create([
            'name' => '红人卡',
            'prefix' => 'MCC',
            'price' => 0.01,
            'rebate' => 0.20,
            'image_url' => 'images/memberships/membership_01.png',
            'description' => '<p><img alt="" src="' . $url . '/images/memberships/红人卡单.jpg" style="height:4000px; width:640px" />示例1</p>'
        ]);
    }
}

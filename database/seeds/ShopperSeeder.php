<?php

use App\Models\Shopper;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ShopperSeeder extends Seeder
{

    public function run()
    {
        $shopper = Shopper::create([
            'name'         => '管理员',
            'nickname'     => '管理员',
            'username'     => 'demo',
            'mobile'       => '10000000000',
            'avatar'       => '/images/default_avatar.jpg',
            'password'     => bcrypt('secret'),
            'is_admin'     => true,
            'role'         => 'seller',
            'seller_level' => 1
        ]);

        $seller_1 = Shopper::create([
            'name'         => '一级代理',
            'nickname'     => '一级代理',
            'username'     => 'demo',
            'mobile'       => '10000000000',
            'avatar'       => '/images/default_avatar.jpg',
            'password'     => bcrypt('secret'),
            'is_admin'     => false,
            'role'         => 'seller',
            'seller_level' => 1
        ]);


        Shopper::create([
            'name'             => '二级代理',
            'nickname'         => '二级代理',
            'username'         => 'demo',
            'mobile'           => '10000000000',
            'avatar'           => '/images/default_avatar.jpg',
            'password'         => bcrypt('secret'),
            'is_admin'         => false,
            'role'             => 'seller',
            'seller_level'     => 2,
            'parent_seller_id' => $seller_1->id
        ]);

        Shopper::create([
            'name'             => '普通',
            'nickname'         => '普通用户',
            'username'         => 'demo',
            'mobile'           => '10000000000',
            'avatar'           => '/images/default_avatar.jpg',
            'password'         => bcrypt('secret'),
            'is_admin'         => false,
        ]);

    }
}

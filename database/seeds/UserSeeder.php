<?php

use App\Models\BackpackUser;
use App\Models\Role;
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UserSeeder extends Seeder
{

    public function run()
    {
        $user = BackpackUser::create([
            'name'     => 'Alex',
            'email'    => 'alex@parse.cn',
            'is_admin' => true,
            'password' => bcrypt('secret')
        ]);

        $user->assignRole(Role::ROLE_ADMIN);

        $user = BackpackUser::create([
            'name'     => 'Kurer',
            'email'    => 'kurer@parse.cn',
            'is_admin' => true,
            'password' => bcrypt('secret')
        ]);
        $user->assignRole(Role::ROLE_ADMIN);

        $user = BackpackUser::create([
            'name'     => 'tj',
            'email'    => '1364544576@qq.com',
            'is_admin' => true,
            'password' => bcrypt('secret')
        ]);
        $user->assignRole(Role::ROLE_ADMIN);

        $user = BackpackUser::create([
            'name'     => '客服',
            'email'    => 'service@demo.com',
            'is_admin' => true,
            'password' => bcrypt('secret')
        ]);
        $user->assignRole(Role::ROLE_CUSTOMER_SERVICE);
    }
}

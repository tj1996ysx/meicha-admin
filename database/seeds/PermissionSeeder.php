<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => Permission::SHOPPER_MANAGEMENT,
            'guard_name' => 'backpack'
        ]);

        Permission::create([
            'name' => Permission::VIEW_DASHBOARD,
            'guard_name' => 'backpack'
        ]);

        Permission::create([
            'name' => Permission::ORDER_MANAGEMENT,
            'guard_name' => 'backpack'
        ]);

        Permission::create([
            'name' => Permission::SYSTEM_MANAGEMENT,
            'guard_name' => 'backpack'
        ]);

        Permission::create([
            'name' => Permission::RESERVE_MANAGEMENT,
            'guard_name' => 'backpack'
        ]);

        Permission::create([
            'name' => Permission::DATA_MANAGEMENT,
            'guard_name' => 'backpack'
        ]);

        /*
        \Spatie\Permission\Models\Permission::create([
            'name' => 'business_beauty_requests',
            'guard_name' => 'backpack'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'business_orders',
            'guard_name' => 'backpack'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'business_seller_rebates',
            'guard_name' => 'backpack'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'business_reservations',
            'guard_name' => 'backpack'
        ]);

        //System
        \Spatie\Permission\Models\Permission::create([
            'name' => 'system_vouchers',
            'guard_name' => 'backpack'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'system_memberships',
            'guard_name' => 'backpack'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'system_hospitals',
            'guard_name' => 'backpack'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'system_beauty_items',
            'guard_name' => 'backpack'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'system_system_logs',
            'guard_name' => 'backpack'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'system_manage',
            'guard_name' => 'backpack'
        ]);
        */
    }
}

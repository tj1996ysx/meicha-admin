<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin_role = Role::create([
            'name' => Role::ROLE_ADMIN,
            'guard_name' => 'backpack',
        ]);
        $permissions = Permission::all();
        $admin_role->givePermissionTo($permissions);

        $cs_role = Role::create([
            'name' => Role::ROLE_CUSTOMER_SERVICE,
            'guard_name' => 'backpack',
        ]);
        $cs_role->givePermissionTo(Permission::RESERVE_MANAGEMENT);
    }
}

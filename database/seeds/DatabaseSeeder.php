<?php

use App\Models\Shopper;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MembershipSeeder::class);
        $this->call(BeautyItemSeeder::class);
        $this->call(VouchersSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(HospitalSeeder::class);
        $this->call(ShopperSeeder::class);
    }
}

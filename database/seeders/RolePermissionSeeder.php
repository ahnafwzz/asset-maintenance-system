<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Role sesuai rancangan MVP
        $superAdmin = Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Asset Manager']);
        Role::create(['name' => 'Maintenance Staff']);
        Role::create(['name' => 'Employee']);
        Role::create(['name' => 'Management']);

        // 2. Membuat Akun Super Admin Pertamamu
        $adminUser = User::create([
            'name' => 'Ahnaf Fawwaz',
            'email' => 'admin@amms.com',
            'password' => Hash::make('password'), // Password default: password
        ]);

        // 3. Menugaskan role Super Admin ke akun tersebut
        $adminUser->assignRole($superAdmin);
    }
}
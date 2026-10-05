<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Daftar Role Sesuai Kesepakatan
        $roles = [
            'Super Admin',
            'Asset Manager',
            'Maintenance Staff',
            'Viewer'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 2. Akun Super Admin (Level 1)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@amms.com'],
            [
                'name' => 'Ahnaf Fawwaz',
                'username' => 'ahnafwzz',
                'password' => Hash::make('12345678'), 
                'approval_status' => 'approved',      
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('Super Admin');

        // 3. Akun Dummy: Asset Manager (Level 2)
        $manager = User::firstOrCreate(
            ['email' => 'manager@amms.com'],
            [
                'name' => 'Budi Manager',
                'username' => 'budimanager',
                'password' => Hash::make('12345678'),
                'approval_status' => 'approved',
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('Asset Manager');

        // 4. Akun Dummy: Maintenance Staff (Level 2)
        $teknisi = User::firstOrCreate(
            ['email' => 'teknisi@amms.com'],
            [
                'name' => 'Joko Teknisi',
                'username' => 'jokoteknisi',
                'password' => Hash::make('12345678'),
                'approval_status' => 'approved',
                'email_verified_at' => now(),
            ]
        );
        $teknisi->assignRole('Maintenance Staff');

        // 5. Akun Dummy: Viewer (Level 3)
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@amms.com'],
            [
                'name' => 'Siti Viewer',
                'username' => 'sitistaff',
                'password' => Hash::make('12345678'),
                'approval_status' => 'unregistered', // Menggunakan status bawaan
                'email_verified_at' => now(),
            ]
        );
        $viewer->assignRole('Viewer');
    }
}
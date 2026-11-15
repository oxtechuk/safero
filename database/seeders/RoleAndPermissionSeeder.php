<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Services\ModuleDiscoveryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Sync all module permissions and system roles
        ModuleDiscoveryService::syncPermissionsToDatabase();

        // 2. Ensure default admin user has the admin role assigned
        $adminUser = User::where('email', 'admin@safero.com')->first();
        if (! $adminUser) {
            $adminUser = User::create([
                'name' => 'Administrator',
                'email' => 'admin@safero.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'job_title_ar' => 'مسؤول النظام العام',
                'job_title_en' => 'System Administrator',
                'is_active' => true,
            ]);
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && $adminUser) {
            $adminUser->assignRole($adminRole);
        }
    }
}

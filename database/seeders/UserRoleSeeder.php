<?php

namespace Database\Seeders;

use App\Models\{User, Role, UserRole};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Super Admin user (first user created)
        $superAdminUser = User::where('staff_id', 'admin')->first();

        // Get all users and roles
        $users = User::all();
        $roles = Role::all();

        // Get specific roles
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole = Role::where('name', 'User')->first();

        foreach ($users as $user) {
            // Assign role based on staff_id or user attributes
            if ($user->staff_id === 'admin') {
                // Assign Super Admin role to admin user
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $superAdminRole->id,
                    'assigned_by' => $superAdminUser->id,
                ]);
            } elseif ($user->staff_id === 'JD001') {
                // Assign Admin role to John Doe
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $adminRole->id,
                    'assigned_by' => $superAdminUser->id,
                ]);
            } else {
                // Assign User role to everyone else
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $userRole->id,
                    'assigned_by' => $superAdminUser->id,
                ]);
            }
        }
    }
}

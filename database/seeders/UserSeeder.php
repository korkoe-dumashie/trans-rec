<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'staff_id' => 'admin',
                'role_id' => 1, // Super Admin role
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'staff_id' => 'ps-002',
                'role_id' => 2, // Admin role
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'staff_id' => 'ps-001',
                'role_id' => 3, // Manager role
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
            UserRole::create([
                'user_id' => User::where('staff_id', $userData['staff_id'])->first()->id,
                'role_id' => 1, // Default to Super Admin for seeding
                'assigned_by' => 1, // Assigned by Super Admin
            ]);
        }
    }
}

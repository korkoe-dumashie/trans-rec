<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'description' => 'Super Administrator with unrestricted access to all system features and settings.',
            ],
            [
                'name' => 'admin',
                'description' => 'Administrator with full access to all resources and permissions.',
            ],
            [
                'name' => 'manager',
                'description' => 'Manager with oversight capabilities and team management permissions.',
            ],
            [
                'name' => 'team_lead',
                'description' => 'Team Lead with project management and team coordination permissions.',
            ],
            [
                'name' => 'accountant',
                'description' => 'Accountant with access to financial records, invoices, and billing information.',
            ],
            [
                'name' => 'support',
                'description' => 'Support staff with access to customer service tools and ticket management.',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}

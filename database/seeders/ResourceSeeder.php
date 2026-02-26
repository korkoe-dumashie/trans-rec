<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            ['name' => 'user', 'description' => 'Manage users, roles, and permissions.'],
            ['name' => 'reports', 'description' => 'Access and generate various reports.'],
            ['name' => 'transactions', 'description' => 'Manage and reconcile financial transactions.'],
            ['name' => 'settings', 'description' => 'Manage application settings and configurations.'],
            ['name' => 'logs', 'description' => 'View and manage activity logs.'],
            ['name' => 'role', 'description' => 'View and manage user roles.'],
            ['name' => 'user_role', 'description' => 'View and manage user roles.'],
        ];

        foreach ($resources as $resourceData) {
            Resource::create($resourceData);
        }
    }
}

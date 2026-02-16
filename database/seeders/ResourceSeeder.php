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
            ['name' => 'User Management', 'description' => 'Manage users, roles, and permissions.'],
            ['name' => 'Reporting', 'description' => 'Access and generate various reports.'],
            ['name' => 'Transaction Reconciliation', 'description' => 'Manage and reconcile financial transactions.'],
            ['name' => 'Settings', 'description' => 'Manage application settings and configurations.'],
        ];

        foreach ($resources as $resourceData) {
            Resource::create($resourceData);
        }
    }
}

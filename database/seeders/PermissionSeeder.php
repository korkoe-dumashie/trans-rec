<?php

namespace Database\Seeders;

use App\Models\{Role, Resource};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();
        $resources = Resource::all();

        foreach ($roles as $role) {
            foreach ($resources as $resource) {
                $role->permissions()->create([
                    'resource_id' => $resource->id,
                    'can_create' => true,
                    'can_read' => true,
                    'can_update' => true,
                    'can_delete' => true,
                    'can_export' => true,
                    'can_import' => true,
                ]);
            }
        }
    }
}

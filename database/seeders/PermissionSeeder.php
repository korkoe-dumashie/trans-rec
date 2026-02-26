<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Role, Resource, Permission};

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all()->keyBy('name');
        $resources = Resource::all()->keyBy('name');

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN - Full Access to Everything
        |--------------------------------------------------------------------------
        */
        foreach ($resources as $resource) {
            Permission::create([
                'role_id' => $roles['super_admin']->id,
                'resource_id' => $resource->id,
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'can_export' => true,
                'can_import' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN - Almost Full Access (No Import/Export on Some)
        |--------------------------------------------------------------------------
        */
        foreach ($resources as $resource) {
            Permission::create([
                'role_id' => $roles['admin']->id,
                'resource_id' => $resource->id,
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'can_export' => true,
                'can_import' => false,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */
        $managerPermissions = [
            'user' => [false, true, false, false, false, false],
            'reports' => [false, true, false, false, true, false],
            'transactions' => [false, true, false, false, true, false],
            'settings' => [false, false, false, false, false, false],
            'logs' => [false, true, false, false, false, false],
            'role' => [false, false, false, false, false, false],
            'user_role' => [false, false, false, false, false, false],
        ];

        $this->assignPermissions($roles['manager'], $resources, $managerPermissions);

        /*
        |--------------------------------------------------------------------------
        | TEAM LEAD
        |--------------------------------------------------------------------------
        */
        $teamLeadPermissions = [
            'user' => [false, true, true, false, false, false],
            'reports' => [false, true, false, false, false, false],
            'transactions' => [false, true, false, false, false, false],
            'settings' => [false, false, false, false, false, false],
            'logs' => [false, true, false, false, false, false],
            'role' => [false, false, false, false, false, false],
            'user_role' => [false, false, false, false, false, false],
        ];

        $this->assignPermissions($roles['team_lead'], $resources, $teamLeadPermissions);

        /*
        |--------------------------------------------------------------------------
        | ACCOUNTANT
        |--------------------------------------------------------------------------
        */
        $accountantPermissions = [
            'user' => [false, false, false, false, false, false],
            'reports' => [false, true, false, false, true, false],
            'transactions' => [true, true, true, false, true, true],
            'settings' => [false, false, false, false, false, false],
            'logs' => [false, true, false, false, false, false],
            'role' => [false, false, false, false, false, false],
            'user_role' => [false, false, false, false, false, false],
        ];

        $this->assignPermissions($roles['accountant'], $resources, $accountantPermissions);

        /*
        |--------------------------------------------------------------------------
        | SUPPORT
        |--------------------------------------------------------------------------
        */
        $supportPermissions = [
            'user' => [false, true, false, false, false, false],
            'reports' => [false, true, false, false, false, false],
            'transactions' => [false, true, false, false, false, false],
            'settings' => [false, false, false, false, false, false],
            'logs' => [true, true, false, false, false, false],
            'role' => [false, false, false, false, false, false],
            'user_role' => [false, false, false, false, false, false],
        ];

        $this->assignPermissions($roles['support'], $resources, $supportPermissions);
    }

    private function assignPermissions($role, $resources, $permissions)
    {
        foreach ($permissions as $resourceName => $values) {
            Permission::create([
                'role_id' => $role->id,
                'resource_id' => $resources[$resourceName]->id,
                'can_create' => $values[0],
                'can_read' => $values[1],
                'can_update' => $values[2],
                'can_delete' => $values[3],
                'can_export' => $values[4],
                'can_import' => $values[5],
            ]);
        }
    }
}

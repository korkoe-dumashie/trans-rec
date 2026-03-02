<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'role_id'                      => 'required|exists:roles,id',
        'permissions'                  => 'required|array|min:1',
        'permissions.*.resource_id'    => 'required|exists:resources,id',
        'permissions.*.can_create'     => 'sometimes|boolean',
        'permissions.*.can_read'       => 'sometimes|boolean',
        'permissions.*.can_update'     => 'sometimes|boolean',
        'permissions.*.can_delete'     => 'sometimes|boolean',
        'permissions.*.can_export'     => 'sometimes|boolean',
        'permissions.*.can_import'     => 'sometimes|boolean',
    ]);

    $role = Role::findOrFail($validated['role_id']);

    $upserted = [];

    foreach ($validated['permissions'] as $permissionData) {
        $permission = Permission::updateOrCreate(
            [
                'role_id'     => $role->id,
                'resource_id' => $permissionData['resource_id'],
            ],
            [
                'can_create' => $permissionData['can_create'] ?? false,
                'can_read'   => $permissionData['can_read']   ?? false,
                'can_update' => $permissionData['can_update'] ?? false,
                'can_delete' => $permissionData['can_delete'] ?? false,
                'can_export' => $permissionData['can_export'] ?? false,
                'can_import' => $permissionData['can_import'] ?? false,
            ]
        );

        $upserted[] = $permission;
    }

    return response()->json([
        'message'     => 'Permissions saved successfully',
        'role'        => $role->name,
        'permissions' => $upserted,
    ], 200);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

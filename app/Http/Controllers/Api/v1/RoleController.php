<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\V1\RoleResource;
use App\Models\{Role,ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log,DB};

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $storeRoleRequest)
    {
        Log::debug(("Got here"));

        DB::beginTransaction();
        $role = Role::create($storeRoleRequest->validated());

        Log::debug("Role created with ID: " . $role->id);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Created New Role',
            'resource_type' => "Role Mgt",
            'metadata' => json_encode([
                'role_id' => $role->id,
                'role_name' => $role->name,
                'user'=> Auth::user()->name,
            ]),
        ]);
        DB::commit();
        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}

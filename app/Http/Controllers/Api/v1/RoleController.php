<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\V1\RoleResource;
use App\Models\{Role,ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log,DB, Validator};

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
public function store(Request $request)
{
    Log::debug("Validating role request");

    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 403);
    }

    try {
        $role = DB::transaction(function () use ($validator) {
            $role = Role::create($validator->validated());

            ActivityLog::create([
                'user_id'       => Auth::id(),
                'action'        => 'Created New Role',
                'resource_type' => 'Role Mgt',
                'metadata'      => json_encode([
                    'role_id'   => $role->id,
                    'role_name' => $role->name,
                    'user'      => Auth::user()->name,
                ]),
            ]);

            return $role;
        });

        return response()->json([
            'success' => true,
            'data'    => new RoleResource($role),
        ], 201);

    } catch (\Throwable $e) {
        Log::error("Role creation failed: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred. Please try again.',
        ], 500);
    }
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

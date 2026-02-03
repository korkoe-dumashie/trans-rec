<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;
use App\Models\UserRole;
use App\Http\Requests\StoreUserRoleRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Resources\V1\UserRoleResource;
use Illuminate\Support\Facades\Log;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info('Fetching all user roles');
        // return new UserRoleResource(UserRole::all());
        $userRoles = UserRole::with(['user', 'role'])->get();
        return UserRoleResource::collection($userRoles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRoleRequest $storeUserRoleRequest)
    {
        Log::debug('assigning roles');
        $userRole = UserRole::create($storeUserRoleRequest->validated());
        return new UserRoleResource($userRole);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRole $userRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRoleRequest $request, UserRole $userRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRole $userRole)
    {
        //
    }
}

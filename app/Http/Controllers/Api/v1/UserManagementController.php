<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\{ActivityLog, User,Auth, UserRole};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth as FacadesAuth, DB,Log, Validator};

class UserManagementController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withTrashed()->get();
        Log::info('Fetched users: ', ['users' => $users]);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    Log::debug("Got here");
    return DB::transaction(function () use ($request) {

        Log::debug("validating");
        $validated = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'staff_id'   => 'required|string',
            'role_id'    => 'required|integer',
        ]);

        if ($validated->fails()) {
            Log::warning('User creation failed: Validation error', ['errors' => $validated->errors()]);
            return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 400);
        }


        $newUser = $validated->validated();


        Log::debug('Validated user data: ', ['data' => $newUser]);
        $existingUser = Auth::where('staff_id', $newUser['staff_id'])->first();
        if ($existingUser) {
            Log::warning('Attempted to create duplicate user with staff_id: ' . $newUser['staff_id']);
            return response()->json(['message' => 'User with this staff ID already exists'], 403);
        }

        $user = User::create([
            'first_name' => $newUser['first_name'],
            'last_name'  => $newUser['last_name'],
            'staff_id'   => $newUser['staff_id'],
            'role_id'    => $newUser['role_id'],
        ]);

        $auth_user = Auth::create([
            'user_id'  => $user->id,
            'staff_id' => $newUser['staff_id'],
            'reset_password' => true,
            'is_active' => true,
        ]);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $newUser['role_id'],
            'assigned_by' => FacadesAuth::user()->id,
        ]);

        ActivityLog::create([
            'user_id'       => $user->id,
            'action'        => 'User Created',
            'resource_type' => 'User Management',
            'metadata'      => json_encode([
                'staff_id'  => $auth_user->staff_id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'timestamp' => now()->toDateTimeString(),
            ]),
        ]);

        Log::debug('Created user role association: ', ['user_id' => $user->id, 'role_id' => $newUser['role_id']]);
        Log::info('Created user: ', ['user' => $user]);

        return new UserResource($user);
    });
}
    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //update user details
        $user = User::findOrFail($id);
        $validated = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name'  => 'sometimes|string|max:255',
            'staff_id'   => 'sometimes|string',
            'role_id'    => 'sometimes|integer',
            'is_active'  => 'sometimes|boolean',

        ]);

        if ($validated->fails()) {
            Log::warning('User update failed: Validation error', ['errors' => $validated->errors()]);
            return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 400);
        }

        $user->update($validated->validated());

        ActivityLog::create([
            'user_id'       => $user->id,
            'action'        => 'User Updated',
            'resource_type' => 'User Management',
            'metadata'      => json_encode([
                'staff_id'  => $user->staff_id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'timestamp' => now()->toDateTimeString(),
            ]),
        ]);

        Log::info('Updated user: ', ['user' => $user]);
        return response()->json(['message' => 'User updated successfully', 'user' => new UserResource($user)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        ActivityLog::create([
            'user_id'       => $user->id,
            'action'        => 'User Deleted',
            'resource_type' => 'User Management',
            'metadata'      => json_encode([
                'staff_id'  => $user->staff_id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'timestamp' => now()->toDateTimeString(),
            ]),
        ]);

        Log::info('Deleted user: ', ['user' => $user]);
        return response()->json(['message' => 'User deleted successfully'], 200);
    }


    public function deactivate(string $id)
    {
        try{
        $user = User::findOrFail($id);
        $user->update(['is_active' => false]);

        $authUser = Auth::where('user_id', $user->id)->first();

        if ($authUser) {
            $authUser->update(['is_active' => false]);
        }

        ActivityLog::create([
            'user_id'       => $user->id,
            'action'        => 'User Deactivated',
            'resource_type' => 'User Management',
            'metadata'      => json_encode([
                'staff_id'  => $user->staff_id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'timestamp' => now()->toDateTimeString(),
            ]),
        ]);

    }catch(\Exception $e){
        Log::error('Error deactivating user: ' . $e->getMessage());
        return response()->json(['message' => 'Error deactivating user: ' . $e->getMessage()], 500);
    }

        Log::info('Deactivated user: ', ['user' => $user]);
        return response()->json(['message' => 'User deactivated successfully', 'user' => new UserResource($user)], 200);
    }

    public function restore(string $id)
    {
        try{
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        ActivityLog::create([
            'user_id'       => $user->id,
            'action'        => 'User Restored',
            'resource_type' => 'User Management',
            'metadata'      => json_encode([
                'staff_id'  => $user->staff_id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'timestamp' => now()->toDateTimeString(),
            ]),
        ]);

        }catch(\Exception $e){
            Log::error('Error restoring user: ' . $e->getMessage());
            return response()->json(['message' => 'Error restoring user: ' . $e->getMessage()], 500);
        }
    }

    

    public function activate(string $id)
    {

    try{
        $user = User::withTrashed()->findOrFail($id)->where('is_active', false)->first();
        $user->update(['is_active' => true]);

        $authUser = Auth::where('user_id', $user->id)->where('is_active', false)->first();

        if ($authUser) {
            $authUser->update(['is_active' => true]);
        }

        ActivityLog::create([
            'user_id'       => $user->id,
            'action'        => 'User Activated',
            'resource_type' => 'User Management',
            'metadata'      => json_encode([
                'staff_id'  => $user->staff_id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'timestamp' => now()->toDateTimeString(),
            ]),
        ]);

    }catch(\Exception $e){
        Log::error('Error activating user: ' . $e->getMessage());
        return response()->json(['message' => 'Error activating user: ' . $e->getMessage()], 500);
    }

        Log::info('Activated user: ', ['user' => $user]);
        return response()->json(['message' => 'User activated successfully', 'user' => new UserResource($user)], 200);
}

}

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
        $users = User::all();
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

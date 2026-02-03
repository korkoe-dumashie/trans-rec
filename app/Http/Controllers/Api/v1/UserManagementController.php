<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\{User,Auth, UserRole};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Log};

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
    public function store(StoreUserRequest $storeUserRequest)
    {

        Log::debug("Got here");
        Log::debug($storeUserRequest->validated());

        DB::transaction(function() use ($storeUserRequest){
        $user = User::create($storeUserRequest->validated());

        $auth_user = Auth::create([
                'user_id'=>$user->id,
                'staff_id'=>$storeUserRequest->staff_id,
                'password'=>bcrypt('defaultPassword123'),
        ]);

        UserRole::create([
            'user_id'=>$user->id,
            'role_id'=>$storeUserRequest->role_id
        ]);

        

        Log::debug('Created user role association: ', ['user_id' => $user->id, 'role_id' => $storeUserRequest->role_id]);
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

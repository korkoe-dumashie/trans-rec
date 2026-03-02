<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreAuthRequest;
use App\Http\Resources\V1\AuthResource;
use App\Http\Resources\V1\UserResource;
use App\Models\{Auth,ActivityLog, UserSession};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash,Log};
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{


public function index()
    {
        $authUsers = Auth::with('user')->get();
        return response()->json(AuthResource::collection($authUsers));
    }


public function authUsers(){
    $authUsers = Auth::with('user')->where('is_active', true)->get();
    return response()->json(AuthResource::collection($authUsers));
}
    public function login(Request $request)
    {

    Log::debug('Got here for login');
        return DB::transaction(function () use ($request) {

            //login validation
            if (!$request->staff_id || !$request->password) {
                Log::warning('Login failed: Missing staff_id or password');
                return response()->json(['message' => 'Staff ID or Password are required'], 400);
                }


            $user = Auth::where('staff_id', $request->staff_id)->first();


            $userId = $user->user->id ?? null;


            if($user->is_active === false) {
                Log::warning('Login failed: Inactive account for staff_id ' . $request->staff_id);
                return response()->json(['message' => 'Your account is inactive. Contact support for assistance.'], 403);
            }


        if (!$user) {
            Log::warning('Login failed: User not found for staff_id ' . $request->staff_id);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if(!Hash::check($request->password, $user->password)) {
            Log::warning('Login failed: Incorrect password for staff_id ' . $request->staff_id);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }



        // Check if user needs to reset password
        if ($user->reset_password) {
            Log::info('User needs to reset password: ' . $user->staff_id);
            return response()->json([
                'message' => 'Password reset required',
                'requires_password_reset' => true,
                'staff_id' => $user->staff_id
            ], 403);
        }


        // User has reset password, proceed with login
        $token = $user->createToken('auth-token')->plainTextToken;

            UserSession::create([
                'user_id' => $userId,
                'last_login_time' => now()->toDateTimeString(),
            ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'User Logged In',
            'resource_type' => "Authentication",
            'metadata' => json_encode([
                'staff_id' => $user->staff_id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'timestamp' => now()->toDateTimeString(),
            ]),
        ]);
        Log::info('User logged in successfully: ' . $user->staff_id);
        Log::debug('Generated token for user ' . $user->staff_id . ': ' . $token);

        return response()->json([
            'message' => 'Login successful',
            'user' => AuthResource::make($user->load('user')),
            'token' => $token
        ], 200);
        });
    }

    public function resetPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'staff_id' => 'required|string',
                'new_password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $user = Auth::where('staff_id', $validated['staff_id'])->first();

            $userId = $user->user->id ?? null;

            Log::debug($userId ? "Found user ID $userId for staff_id " . $validated['staff_id'] : "No user found for staff_id " . $validated['staff_id']);

            if (!$user) {
                Log::warning('Password reset failed: User not found for staff_id ' . $validated['staff_id']);
                return response()->json(['message' => 'User not found. Contact support if this is an error.'], 404);
            }

            // Verify old password if user has already reset (optional security measure)
            if ($user->reset_password === false) {
                return response()->json([
                    'message' => 'Your password has already been set. Please log in and change your password from your profile, use the forgot password feature, or contact support to reset your account.'
                ], 403);
            }

            if ($user->reset_password && isset($validated['old_password'])) {
                if (!Hash::check($validated['old_password'], $user->password)) {
                    Log::warning('Password reset failed: Invalid old password for staff_id ' . $validated['staff_id']);
                    return response()->json(['message' => 'Invalid old password'], 401);
                }
            }

            // Update password and set reset_password to true
            $user->password = Hash::make($validated['new_password']);
            $user->reset_password = false;
            $user->update();

            Log::debug("User updated: ". $user);

            // Generate token for immediate login after password reset
            $token = $user->createToken('auth-token')->plainTextToken;

            Log::info('Password reset successful for staff_id: ' . $user->staff_id);


            UserSession::create([
                'user_id' => $userId,
                'last_login_time' => now()->toDateTimeString(),
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Password Reset',
                'resource_type' => "Authentication",
                'metadata' => json_encode([
                    'staff_id' => $user->staff_id,
                    'user_name' => $user->name,
                    'timestamp' => now()->toDateTimeString(),
                ]),
            ]);
            return response()->json([
                'message' => 'Password reset successful',
                'user' => $user->load('user'),
                'token' => $token
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return response()->json(['message' => 'Error resetting password: ' . $e->getMessage()], 500);
        }
    }


    public function resetUserPassword(Request $request, string $staff_id){
        try{
            //patch request to make user reset their password on first login
            $user = Auth::where('staff_id', $staff_id)->first();
            if (!$user) {
                Log::warning('Password reset failed: User not found for staff_id ' . $staff_id);
                return response()->json(['message' => 'User not found. Contact support if this is an error.'], 404);
            }

            $user->reset_password = true;
            $user->update();
            Log::info('Password reset flag set for staff_id: ' . $user->staff_id);
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Password Reset Flag Set',
                'resource_type' => "Authentication",
                'metadata' => json_encode([
                    'staff_id' => $user->staff_id,
                    'user_name' => $user->name,
                    'timestamp' => now()->toDateTimeString(),
                ]),
            ]);
            return response()->json(['message' => 'User password reset flag set successfully. User will be required to reset password on next login.'], 200);
        }
        catch(\Exception $e){
                Log::error('Error resetting user password: ' . $e->getMessage());
                return response()->json(['message' => 'Error resetting user password: ' . $e->getMessage()], 500);
        }
    }


    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'staff_id' => 'required|string',
                'old_password' => 'required|string',
                'new_password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $user = Auth::where('staff_id', $validated['staff_id'])->first();

            if (!$user) {
                Log::warning('Password change failed: User not found for staff_id ' . $validated['staff_id']);
                return response()->json(['message' => 'User not found. Contact support if this is an error.'], 404);
            }

            if (!Hash::check($validated['old_password'], $user->password)) {
                Log::warning('Password change failed: Invalid old password for staff_id ' . $validated['staff_id']);
                return response()->json(['message' => 'Invalid old password'], 401);
            }

            // Update password
            $user->password = Hash::make($validated['new_password']);
            $user->update();

            Log::info('Password change successful for staff_id: ' . $user->staff_id);

            $loginTime = now()->toDateTimeString();

            UserSession::create([
                'user_id' => $user->id,
                'last_login_time' => $loginTime,
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Password Changed',
                'resource_type' => "Authentication",
                'metadata' => json_encode([
                    'staff_id' => $user->staff_id,
                    'user_name' => $user->name,
                    'timestamp' => now()->toDateTimeString(),
                ]),
            ]);

            return response()->json([
                'message' => 'Password changed successfully',
                'user' => $user->load('user'),
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error changing password: ' . $e->getMessage());
            return response()->json(['message' => 'Error changing password: ' . $e->getMessage()], 500);
        }
    }
}

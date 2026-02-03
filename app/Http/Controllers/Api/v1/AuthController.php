<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreAuthRequest;
use App\Models\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(StoreAuthRequest $storeAuth)
    {
        // Authentication logic here
    }

    public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
    {
        // Password reset logic here
        Log::debug('Resetting password');

        $user = $resetPasswordRequest->validated();
        try{
            Log::debug('Validated data: '.json_encode($user));
            
        }
        catch(\Exception $e){
            Log::error('Error resetting password: '.$e->getMessage());
            return response()->json(['message'=>'Error resetting password'],500);
        }
    }
}

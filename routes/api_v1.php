<?php

use App\Http\Controllers\Api\v1\{AuthController,RoleController,UserManagementController, UserRoleController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::put('reset-password', 'resetPassword');
    });

    Route::get('users', [AuthController::class, 'authUsers']);




Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('user', UserManagementController::class)->middleware('permission:user,read,create,update,delete,export,import');
    Route::apiResource('role',RoleController::class);

    Route::apiResource('user-role',UserRoleController::class);
});



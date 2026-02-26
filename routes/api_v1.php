<?php

use App\Http\Controllers\Api\v1\{ActivityLogController, AuthController,RoleController,UserManagementController, UserRoleController};
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
    // Route::apiResource('user', UserManagementController::class);
    // Route::apiResource('role',RoleController::class);

    // Route::apiResource('user-role',UserRoleController::class);


    Route::controller(ActivityLogController::class)->group(function () {
        Route::get('getLogs', 'index')->middleware('permission:logs,read');
        Route::post('storeLogs', 'store')->middleware('permission:logs,create');
    });


    Route::controller(UserManagementController::class)->group(function () {
        Route::post('user', 'store')->middleware('permission:user,create');
        Route::get('user', 'index')->middleware('permission:user,read');
        Route::get('user/{id}', 'show')->middleware('permission:user,read');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::post('role', 'store')->middleware('permission:role,create');
        Route::get('role', 'index')->middleware('permission:role,read');
    });


    Route::controller(UserRoleController::class)->group(function () {
        Route::post('storeUserRole', 'store')->middleware('permission:user_role,create');
        Route::get('getUserRoles', 'index')->middleware('permission:user_role,read');
    });




});




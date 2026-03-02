<?php

use App\Http\Controllers\Api\v1\{ActivityLogController, AuthController, PermissionController, RoleController,UserManagementController, UserRoleController, UserSessionsController};
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

    Route::patch('changePassword', [AuthController::class, 'changePassword']);
    Route::controller(AuthController::class)->group(function () {
        Route::get('allUsers', 'index');
        // Route::
    });

    Route::controller(ActivityLogController::class)->group(function () {
        Route::get('getLogs', 'index')->middleware('permission:logs,read');
        Route::post('storeLogs', 'store')->middleware('permission:logs,create');
    });


    Route::controller(UserSessionsController::class)->group(function () {
        Route::get('sessions', 'index')->middleware('permission:logs,read');
    });


    Route::controller(UserManagementController::class)->group(function () {
        Route::post('user', 'store')->middleware('permission:user,create');
        Route::get('user', 'index')->middleware('permission:user,read');
        Route::get('user/{id}', 'show')->middleware('permission:user,read');
        Route::put('user/{id}', 'update')->middleware('permission:user,update');
        Route::delete('user/{id}', 'destroy')->middleware('permission:user,delete');
        Route::patch('user/{id}/activate', 'activate')->middleware('permission:user,update');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::post('role', 'store')->middleware('permission:role,create');
        Route::get('role', 'index')->middleware('permission:role,read');
    });


    Route::controller(UserRoleController::class)->group(function () {
        Route::post('storeUserRole', 'store')->middleware('permission:user_role,create');
        Route::get('getUserRoles', 'index')->middleware('permission:user_role,read');
    });



    Route::controller(PermissionController::class)->group(function(){
        Route::post('storePermission', 'store')->middleware('permission:permission,create');
    });


});




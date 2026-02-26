<?php

namespace App\Http\Middleware\V1;

use App\Models\{Auth,Permission,User,Role,Resource};
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,string $resourceName, string $action): Response
    {

    Log::debug("Checking permissions for resource: $resourceName and action: $action");

    $authUser = Auth::where('staff_id', $request->user()->staff_id)->first();

    Log::debug('Checking permissions for user: '.$authUser);
    if (!$authUser) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // $userRoles = $authUser->user->role()->pluck('id')->toArray();
$userRoleId = $authUser->user->role_id;
if (!$userRoleId) {
    return response()->json(['message' => 'No role assigned'], 403);
}


    Log::info('User Role ID: ' . $userRoleId);

    $resource = Resource::where('name',$resourceName)->first();

    if(!$resource || !$resource->is_active){
        return response()->json(['message' => 'Resource not found or inactive'], 403);
    }


            $hasPermission = Permission::where('role_id', $userRoleId)
    ->where('resource_id', $resource->id)
    ->where("can_$action", true)
    ->exists();

        if (!$hasPermission) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }



        return $next($request);
    }
}

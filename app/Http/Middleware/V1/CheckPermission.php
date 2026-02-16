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

    $authUser = Auth::where('staff_id', $request->user()->staff_id)->first();
    if (!$authUser) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $userRoles = $authUser->user->role()->pluck('id')->toArray();

    Log::info('User Roles: ' . implode(', ', $userRoles));

    $resource = Resource::where('name',$resourceName)->first();

    if(!$resource || !$resource->is_active){
        return response()->json(['message' => 'Resource not found or inactive'], 404);
    }


            $hasPermission = Permission::whereIn('role_id', $userRoles)
            ->where('resource_id', $resource->id)
            ->where("can_$action", true)
            ->exists();

        if (!$hasPermission) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }



        return $next($request);
    }
}

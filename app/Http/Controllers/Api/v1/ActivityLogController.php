<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Http\Requests\{StoreActivityLogRequest,UpdateActivityLogRequest};
use App\Http\Resources\V1\ActivityResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $logs = ActivityLog::with('user')->paginate(10);

    return ActivityResource::collection($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $logs = Validator::make($request->all(), [
            'user_id' => Auth::user()->id(),
            'action' => 'required|string|max:255',
            'resource_type' => 'required|string|max:255',
            'metadata' => 'nullable|json',
        ]);

        if ($logs->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $logs->errors(),
            ], 403);
        }

        $log = ActivityLog::create($logs->validated());
        return response()->json([
            'success' => true,
            'data'    => new ActivityResource($log),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityLogRequest $request, ActivityLog $activityLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityLog $activityLog)
    {
        //
    }
}

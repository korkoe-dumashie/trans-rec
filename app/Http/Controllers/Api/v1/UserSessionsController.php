<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use Illuminate\Http\Request;

class UserSessionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = UserSession::with('user')->paginate(10);
        return response()->json([
            'success' => true,
            'data'    => $sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'user_id' => $session->user_id,
                    'last_login_time' => $session->last_login_time,
                    'user' => [
                        'id' => $session->user->id,
                        'first_name' => $session->user->first_name,
                        'last_name' => $session->user->last_name,
                        'staff_id' => $session->user->staff_id,
                        'role' => $session->user->role ? $session->user->role->name : null,
                        'is_active' => $session->user->is_active,
                    ],
                ];
            })]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSession $userSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserSession $userSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSession $userSession)
    {
        //
    }
}

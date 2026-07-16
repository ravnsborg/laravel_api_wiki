<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdatePreferredEntityResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        $user = User::with('entity')->find($id);

        if (! $user) {
            return response()->json(
                ['message' => 'User not found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        return response()->json(
            ['user' => new UserResource($user)],
            self::HTTP_STATUS_CODES['success']
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    public function update_entity(UpdatePreferredEntityResource $request): object
    {
        auth()->user()->update(['preferred_entity_id' => $request->input('entity_id')]);

        return response()->json([
            'message' => 'Users default entity was updated',
        ], self::HTTP_STATUS_CODES['created']);
    }
}

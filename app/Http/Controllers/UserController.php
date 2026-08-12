<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdatePreferredEntityResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(int $id)
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

    public function update_entity(UpdatePreferredEntityResource $request, int $id): object
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], self::HTTP_STATUS_CODES['not_found']);
        }

        $user->update([
            'preferred_entity_id' => $request->input('entity_id'),
        ]);

        return response()->json([
            'message' => 'Users default entity was updated',
        ], self::HTTP_STATUS_CODES['created']);
    }
}

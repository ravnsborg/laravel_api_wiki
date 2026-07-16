<?php

namespace App\Http\Controllers;

use App\Http\Requests\Entities\CreateEntityResource;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): object
    {
        $entities = Entity::get();

        if ($entities->isEmpty()) {
            return response()->json(
                ['message' => 'Entities not found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        return response()->json(
            EntityResource::collection($entities),
            self::HTTP_STATUS_CODES['success']
        );
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
    public function store(CreateEntityResource $request): object
    {
        $entity = Entity::create([
            'title' => $request->input('title'),
            'user_id' => Auth::user()->id,
        ]);

        if (! $entity) {
            return response()->json(
                ['message' => 'Could not create new entity'],
                self::HTTP_STATUS_CODES['server_error']
            );
        }
        auth()->user()->update(['preferred_entity_id' => $entity->id]);

        return response()->json(
            new EntityResource($entity),
            self::HTTP_STATUS_CODES['success']
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}

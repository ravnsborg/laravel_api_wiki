<?php

namespace App\Http\Controllers;

use App\Http\Requests\Links\CreateUpdateLinkRequest;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): object
    {
        // Get all links for now regardless of entity
        $links = Link::orderBy('title')->get();
        // $links = Link::where('entity_id', Auth::user()->preferred_entity_id)->get();

        if ($links->isEmpty()) {
            return response()->json(
                ['message' => 'Favorites not found'],
                self::HTTP_STATUS_CODES['success']
            );
        }

        return response()->json(
            LinkResource::collection($links),
            self::HTTP_STATUS_CODES['success']
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUpdateLinkRequest $request): object
    {
        $link = Link::create($request->validated());

        return response()->json([
            'link' => new LinkResource($link),
        ], self::HTTP_STATUS_CODES['created']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): object
    {
        $link = Link::find($id);

        if (! $link) {
            return response()->json(
                ['message' => 'Link not found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        return response()->json(
            ['link' => new LinkResource($link)],
            self::HTTP_STATUS_CODES['success']
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, CreateUpdateLinkRequest $request): object
    {
        $link = Link::find($id);

        if (! $link) {
            return response()->json(
                ['message' => 'Link not found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        $link->update($request->validated());

        return response()->json([
            'link' => new LinkResource($link),
        ], self::HTTP_STATUS_CODES['success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): object
    {
        $deleted = Link::destroy($id);

        return response()->json(
            ['message' => 'Link deleted successfully'],
            self::HTTP_STATUS_CODES['success']
        );
    }
}

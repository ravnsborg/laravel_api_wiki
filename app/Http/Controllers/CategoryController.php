<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateUpdateCategoryRequest;
use App\Http\Requests\Categories\ListCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Categories\CategoryService;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListCategoryRequest $request, CategoryService $categoryService): object
    {

        $categories = $categoryService->getMany($request->getIncludeParameterValue());

        if ($categories->isEmpty()) {
            return response()->json(
                ['message' => 'Categories not found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        return response()->json(
            CategoryResource::collection($categories),
            self::HTTP_STATUS_CODES['success']
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUpdateCategoryRequest $request): object
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'category' => new CategoryResource($category),
            ], self::HTTP_STATUS_CODES['created']);
        } catch (\Exception $e) {
            Log::critical('Error creating new category'.$e->getMessage());

            return response()->json([
                'message' => 'Failed to create category.',
            ], self::HTTP_STATUS_CODES['server_error']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, ListCategoryRequest $request, CategoryService $categoryService): object
    {
        $category = $categoryService->getById($id, $request->getIncludeParameterValue());

        if (! $category) {
            return response()->json(
                ['message' => 'Category not found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        return response()->json(
            ['category' => new CategoryResource($category)],
            self::HTTP_STATUS_CODES['success']
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, CreateUpdateCategoryRequest $request): object
    {
        try {
            $category = Category::updateOrCreate(['id' => $id], $request->validated());

            return response()->json([
                'category' => new CategoryResource($category),
            ], self::HTTP_STATUS_CODES['created']);
        } catch (\Exception $e) {
            Log::critical('Error updating category'.$e->getMessage());

            return response()->json([
                'message' => 'Failed to create category',
            ], self::HTTP_STATUS_CODES['server_error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): object
    {
        try {
            Category::destroy($id);

            return response()->json(
                ['message' => 'Category deleted successfully'],
                self::HTTP_STATUS_CODES['success']
            );
        } catch (\Exception $e) {
            Log::critical('Error deleting category'.$e->getMessage());

            return response()->json([
                'message' => 'Failed to delete category.',
            ], self::HTTP_STATUS_CODES['server_error']);
        }
    }
}

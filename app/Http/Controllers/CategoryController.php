<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateUpdateCategoryRequest;
use App\Http\Requests\Categories\ListCategoryRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Models\Article;
use App\Models\Category;
use App\Services\Categories\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                self::HTTP_STATUS_CODES['success']
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
        $category = Category::create([
            'entity_id' => Auth::user()->preferred_entity_id,
            'title' => $request->input('title'),
        ]);

        return response()->json([
            'category' => new CategoryResource($category),
        ], self::HTTP_STATUS_CODES['created']);
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
        $category = Category::updateOrCreate(['id' => $id], $request->validated());

        return response()->json([
            'category' => new CategoryResource($category),
        ], $category->wasRecentlyCreated
            ? self::HTTP_STATUS_CODES['created']
            : self::HTTP_STATUS_CODES['success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): object
    {
        $deleted = Category::destroy($id);

        return response()->json(
            ['message' => 'Category deleted successfully'],
            self::HTTP_STATUS_CODES['success']
        );
    }

    public function category_articles(int $id, Request $request) // : object
    {
        $articles = Article::with('category')->whereRelation('category', 'id', $id)->get();

        if (! $articles) {
            return response()->json(
                ['message' => 'No keyword matches found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        return response()->json(
            ArticleResource::collection($articles),
            self::HTTP_STATUS_CODES['success']
        );
    }
}

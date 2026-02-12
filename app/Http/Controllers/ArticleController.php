<?php

namespace App\Http\Controllers;

use App\Http\Requests\Articles\CreateUpdateArticleRequest;
use App\Http\Requests\Articles\ListArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\Articles\ArticleService;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListArticleRequest $request, ArticleService $articleService): object
    {
        $articles = $articleService->getMany($request->getIncludeParameterValue());

        if ($articles->isEmpty()) {
            return response()->json(
                ['message' => 'Articles not found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        return response()->json(
            ArticleResource::collection($articles),
            self::HTTP_STATUS_CODES['success']
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUpdateArticleRequest $request): object
    {
        try {
            $article = Article::create($request->validated());

            return response()->json([
                'article' => new ArticleResource($article),
            ], self::HTTP_STATUS_CODES['created']);
        } catch (\Exception $e) {
            Log::critical('Error creating new article'.$e->getMessage());

            return response()->json([
                'message' => 'Failed to create article',
            ], self::HTTP_STATUS_CODES['server_error']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, ListArticleRequest $request, ArticleService $articleService): object
    {
        $article = $articleService->getById($id, $request->getIncludeParameterValue());

        if (! $article) {
            return response()->json(
                ['message' => 'Article not found'],
                self::HTTP_STATUS_CODES['not_found']
            );
        }

        return response()->json(
            ['article' => new ArticleResource($article)],
            self::HTTP_STATUS_CODES['success']
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, CreateUpdateArticleRequest $request): object
    {
        try {
            $article = Article::updateOrCreate(['id' => $id], $request->validated());

            return response()->json([
                'article' => new ArticleResource($article),
            ], self::HTTP_STATUS_CODES['created']);
        } catch (\Exception $e) {
            Log::critical('Error updating article'.$e->getMessage());

            return response()->json([
                'message' => 'Failed to create article',
            ], self::HTTP_STATUS_CODES['server_error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): object
    {
        try {
            Article::destroy($id);

            return response()->json(
                ['message' => 'Article deleted successfully'],
                self::HTTP_STATUS_CODES['success']
            );
        } catch (\Exception $e) {
            Log::critical('Error deleting article'.$e->getMessage());

            return response()->json([
                'message' => 'Failed to delete article',
            ], self::HTTP_STATUS_CODES['server_error']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Articles\CreateUpdateArticleRequest;
use App\Http\Requests\Articles\ListArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\Articles\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                self::HTTP_STATUS_CODES['success']
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
        $article = Article::create($request->validated());

        return response()->json([
            'article' => new ArticleResource($article),
        ], self::HTTP_STATUS_CODES['created']);
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
        $article = Article::query();

        if ($request->getIncludeParameterValue()) {
            $article->with($request->getIncludeParameterValue());
        }

        $article = $article->updateOrCreate(['id' => $id], $request->validated());

        return response()->json([
            'article' => new ArticleResource($article),
        ], $article->wasRecentlyCreated
            ? self::HTTP_STATUS_CODES['created']
            : self::HTTP_STATUS_CODES['success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): object
    {
        $deleted = Article::destroy($id);

        return response()->json(
            ['message' => 'Article deleted successfully'],
            self::HTTP_STATUS_CODES['success']
        );
    }

    public function search(Request $request): object
    {
        $searchTerm = $request->query('q');

        $articles = Article::with('category')
            ->where(function ($query) use ($searchTerm) {
                $query->where('articles.title', 'like', "%{$searchTerm}%")
                    ->orWhere('articles.body', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('category', function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%");
            })
            ->get();

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

    /**
     * Get favorites associated to the user's preferred entity id
     */
    public function favorites(): object
    {
        $articles = Article::where('is_favorite', true)
            ->whereRelation('category', 'entity_id', Auth::user()->preferred_entity_id)
            ->orderBy('title')
            ->get();

        if ($articles->isEmpty()) {
            return response()->json(
                ['message' => 'Articles not found'],
                self::HTTP_STATUS_CODES['success']
            );
        }

        return response()->json(
            ArticleResource::collection($articles),
            self::HTTP_STATUS_CODES['success']
        );
    }
}

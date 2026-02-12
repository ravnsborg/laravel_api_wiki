<?php

namespace App\Services\Articles;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleService
{
    private object $article;

    public function __construct()
    {
        $this->article = Article::query();
    }

    /**
     * Retrieve one article record
     */
    public function getById(int $id, ?string $joinedTable = null): ?Article
    {

        $this->appendTable($joinedTable);

        return $this->article->find($id);
    }

    /**
     * Retrieve multiple article records
     */
    public function getMany(?string $joinedTable = null): Collection
    {
        $this->appendTable($joinedTable);

        return $this->article->get();
    }

    /**
     * Join table to results if requested
     */
    private function appendTable(?string $joinedTable = null): void
    {
        if ($joinedTable) {
            $this->article->with($joinedTable);
        }
    }
}

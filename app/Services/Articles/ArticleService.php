<?php

namespace App\Services\Articles;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleService
{
    /**
     * Retrieve one article record
     */
    public function getById(int $id, ?string $joinedTable = null): ?Article
    {
        $query = Article::query();

        if ($joinedTable) {
            $query->with($joinedTable);
        }

        return $query->find($id);
    }

    /**
     * Retrieve multiple article records
     */
    public function getMany(?string $joinedTable = null): Collection
    {
        $query = Article::query();

        if ($joinedTable) {
            $query->with($joinedTable);
        }

        return $query->get();
    }
}

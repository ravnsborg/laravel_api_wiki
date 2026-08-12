<?php

namespace App\Services\Categories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    /**
     * Retrieve one category record
     */
    public function getById(int $id, ?string $joinedTable = null): ?Category
    {
        $query = Category::query();

        if ($joinedTable) {
            $query->with($joinedTable);
        }

        return $query->find($id);
    }

    /**
     * Retrieve multiple category records
     */
    public function getMany(?string $joinedTable = null): Collection
    {
        $query = Category::query();

        if ($joinedTable) {
            $query->with($joinedTable);
        }

        return $query->orderBy('title')
            ->get();
    }
}

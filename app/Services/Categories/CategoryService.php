<?php

namespace App\Services\Categories;

use App\Models\Category;

class CategoryService
{
    private object $category;

    public function __construct()
    {
        $this->category = Category::query();
    }

    /**
     * Retrieve one category record
     */
    public function getById(int $id, ?string $joinedTable = null)
    {
        $this->appendTable($joinedTable);

        return $this->category->find($id);
    }

    /**
     * Retrieve multiple category records
     */
    public function getMany(?string $joinedTable = null)
    {
        $this->appendTable($joinedTable);

        return $this->category->get();
    }

    /**
     * Join table to results if requested
     */
    private function appendTable(?string $joinedTable = null): void
    {
        if ($joinedTable) {
            $this->category->with($joinedTable);
        }
    }
}

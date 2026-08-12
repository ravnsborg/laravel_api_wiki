<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = ['category_id', 'title', 'body', 'url', 'is_favorite', 'sort_order'];

    protected $casts = [
        'is_favorite' => 'boolean',
    ];

    /*
    * Boot the model and add a global scope to filter articles based on the user's preferred entity.
    * ** To override this global scope, use the `withoutGlobalScope` method in your query.
    * ** Example: Article::withoutGlobalScope('preferredEntity')->get();
    */
    protected static function booted()
    {
        static::addGlobalScope('preferredEntity', function (Builder $builder) {
            if (Auth::check()) {
                $builder->whereRelation('category', 'entity_id', Auth::user()->preferred_entity_id);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

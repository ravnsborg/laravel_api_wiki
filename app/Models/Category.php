<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['title', 'entity_id'];

    /*
    * Boot the model and add a global scope to filter categories based on the user's preferred entity.
    * ** To override this global scope, use the `withoutGlobalScope` method in your query.
    * ** Example: Category::withoutGlobalScope('preferredEntity')->get();
    */
    protected static function booted()
    {
        static::addGlobalScope('preferredEntity', function (EloquentBuilder $builder) {
            if (Auth::check()) {
                $builder->where('entity_id', Auth::user()->preferred_entity_id);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}

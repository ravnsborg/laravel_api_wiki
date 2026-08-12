<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Link extends Model
{
    use HasFactory;

    protected $table = 'links';

    protected $fillable = ['entity_id', 'title', 'url'];

    /*
    * Boot the model and add a global scope to filter links based on the user's preferred entity.
    ** To override this global scope, use the `withoutGlobalScope` method in your query.
    ** Example: Link::withoutGlobalScope('preferredEntity')->get();
    */
    protected static function booted()
    {
        static::addGlobalScope('preferredEntity', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('entity_id', Auth::user()->preferred_entity_id);
            }
        });
    }
}

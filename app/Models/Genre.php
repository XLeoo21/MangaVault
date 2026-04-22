<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Genre extends Model
{
    use HasFactory;

    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(Manga::class)->withTimestamps();
    }
}

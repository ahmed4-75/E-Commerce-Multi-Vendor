<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Translation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
    */
    protected $fillable = [
        'name',
        'description',
        'lang',
        'translationable_id',
        'translationable_type'
    ];

    /**
     * Get the parent translationable model (category or product).
    */
    public function translationable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTypeAttribute()
    {
        return strtolower(class_basename($this->translationable_type));
    }
}

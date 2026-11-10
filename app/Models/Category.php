<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    public $translatable = ['name'];

    protected string $slugSource = 'name';

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
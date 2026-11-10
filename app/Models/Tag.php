<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasTranslations, HasSlug;

    protected $fillable = [
        'name',
        'slug',
    ];

    public $translatable = ['name'];

    protected string $slugSource = 'name';

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }
}
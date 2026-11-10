<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', $locale, false) ?? $this->title,
            'excerpt' => $this->getTranslation('excerpt', $locale, false) ?? $this->excerpt,
            'slug' => $this->slug,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'views' => $this->views,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toIso8601String(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Article */
class ArticleListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'slug' => $this->slug,
            'image' => $this->image ? asset('storage/'.$this->image) : null,
            'views' => $this->views,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toIso8601String(),
            'comments_count' => $this->whenCounted('comments'),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => new UserResource($this->whenLoaded('user')),
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Article */
class ArticleDetailResource extends JsonResource
{
    protected $related = null;

    public function withRelatedArticles($related): static
    {
        $this->related = $related;

        return $this;
    }

    public function toArray(Request $request): array
    {
        $related = $this->related
            ?? ($this->resource->relationLoaded('related_articles')
                ? $this->resource->getRelation('related_articles')
                : null);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'slug' => $this->slug,
            'image' => $this->image ? asset('storage/'.$this->image) : null,
            'views' => $this->views,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toIso8601String(),
            'comments_count' => $this->whenCounted('comments'),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => new UserResource($this->whenLoaded('user')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'related_articles' => $this->when(
                $related !== null,
                fn () => ArticleListResource::collection($related)
            ),
        ];
    }
}

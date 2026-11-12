<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale, false) ?: ($this->getTranslation('name', 'tm', false) ?: $this->name),
            'translations' => $this->getTranslations('name'),
            'slug' => $this->slug,
            'sort_order' => $this->sort_order,
            'articles_count' => $this->whenCounted('articles'),
        ];
    }
}

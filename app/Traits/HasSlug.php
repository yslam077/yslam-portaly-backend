<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function ($model) {
            $sourceField = isset($model->title) ? 'title' : 'name';
            $fieldValue = $model->{$sourceField} ?? null;

            if (is_array($fieldValue)) {
                $locale = app()->getLocale();
                $sourceValue = $fieldValue[$locale] ?? reset($fieldValue) ?? '';
            } else {
                $sourceValue = (string) $fieldValue;
            }

            if (empty($model->slug) && !empty($sourceValue)) {
                $slug = Str::slug($sourceValue);
                $originalSlug = $slug;
                $count = 1;

                while (self::where('slug', $slug)->when($model->exists, function ($query) use ($model) {
                    return $query->where($model->getKeyName(), '!=', $model->getKey());
                })->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }

                $model->slug = $slug;
            }
        });
    }
}
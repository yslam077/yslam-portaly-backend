<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (! empty($model->slug)) {
                return;
            }

            $field = property_exists($model, 'slugSource') ? $model->slugSource : 'name';

            if (method_exists($model, 'isTranslatableAttribute') && $model->isTranslatableAttribute($field)) {
                $translations = array_filter($model->getTranslations($field));
                $source = $translations['tm']
                    ?? $translations[app()->getLocale()]
                    ?? (reset($translations) ?: '');
            } else {
                $source = (string) $model->getAttribute($field);
            }

            $base = Str::slug($source);

            if ($base === '') {
                $base = Str::lower(class_basename($model)) . '-' . Str::lower(Str::random(6));
            }

            $slug = $base;
            $count = 1;

            while (static::slugExists($model, $slug)) {
                $slug = $base . '-' . $count++;
            }

            $model->slug = $slug;
        });
    }

    protected static function slugExists($model, string $slug): bool
    {
        $query = static::query();

        if (in_array(SoftDeletes::class, class_uses_recursive($model), true)) {
            $query->withTrashed();
        }

        return $query->where('slug', $slug)
            ->when($model->exists, fn ($q) => $q->where($model->getKeyName(), '!=', $model->getKey()))
            ->exists();
    }
}
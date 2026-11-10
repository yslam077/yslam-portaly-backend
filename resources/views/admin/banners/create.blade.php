@extends('admin.layouts.app')

@section('header', isset($banner) ? 'Редактировать баннер' : 'Создать баннер')

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
    <form action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($banner))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Заголовок (необязательно)</label>
            <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Ссылка (URL)</label>
            <input type="url" name="link" value="{{ old('link', $banner->link ?? '') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Позиция на сайте</label>
            <input type="text" name="position" value="{{ old('position', $banner->position ?? 'home_top') }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Изображение баннера</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Активен (показывать на сайте)</label>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>
@endsection
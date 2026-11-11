@extends('admin.layouts.app')

@section('header', 'Создать статью')

@section('content')
<div class="card border-0 shadow-sm p-4">
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Заголовок (TM)</label>
                <input type="text" name="title[tm]" value="{{ old('title.tm') }}" required class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Заголовок (RU)</label>
                <input type="text" name="title[ru]" value="{{ old('title.ru') }}" required class="form-control">
            </div>
            <div class="col-md-12">
                <label class="form-label">Заголовок (EN)</label>
                <input type="text" name="title[en]" value="{{ old('title.en') }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Категория</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Статус</label>
                <select name="status" class="form-select" required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Черновик</option>
                    <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>На проверке</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Опубликовано</option>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Краткое описание (TM)</label>
                <textarea name="excerpt[tm]" rows="2" class="form-control">{{ old('excerpt.tm') }}</textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label">Краткое описание (RU)</label>
                <textarea name="excerpt[ru]" rows="2" class="form-control">{{ old('excerpt.ru') }}</textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label">Краткое описание (EN)</label>
                <textarea name="excerpt[en]" rows="2" class="form-control">{{ old('excerpt.en') }}</textarea>
            </div>

            <div class="col-md-12">
                <label class="form-label">Содержимое (TM)</label>
                <textarea name="content[tm]" rows="5" required class="form-control">{{ old('content.tm') }}</textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label">Содержимое (RU)</label>
                <textarea name="content[ru]" rows="5" required class="form-control">{{ old('content.ru') }}</textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label">Содержимое (EN)</label>
                <textarea name="content[en]" rows="5" class="form-control">{{ old('content.en') }}</textarea>
            </div>

            <div class="col-md-12">
                <label class="form-label">Теги</label>
                <select name="tags[]" class="form-select" multiple size="8">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Изображение</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="col-md-12">
                <div class="form-check">
                    <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured">
                    <label class="form-check-label" for="is_featured">Рекомендованная статья</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>
@endsection
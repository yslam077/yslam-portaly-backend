@extends('admin.layouts.app')

@section('header', isset($category) ? 'Редактировать категорию' : 'Создать категорию')

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Название (TM)</label>
            <input type="text" name="name[tm]" value="{{ old('name.tm', $category->getTranslation('name', 'tm', false) ?? '') }}" required class="form-control">
        </div>

        <div class="mb-4">
            <label class="form-label">Название (RU)</label>
            <input type="text" name="name[ru]" value="{{ old('name.ru', $category->getTranslation('name', 'ru', false) ?? '') }}" required class="form-control">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>
@endsection
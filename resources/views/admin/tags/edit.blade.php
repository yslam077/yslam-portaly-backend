@extends('admin.layouts.app')

@section('header', 'Редактировать тег')

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width: 700px;">
    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Название (TM)</label>
                <input type="text" name="name[tm]" value="{{ old('name.tm', $tag->getTranslation('name', 'tm', false) ?? '') }}" required class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Название (RU)</label>
                <input type="text" name="name[ru]" value="{{ old('name.ru', $tag->getTranslation('name', 'ru', false) ?? '') }}" required class="form-control">
            </div>
            <div class="col-md-12">
                <label class="form-label">Название (EN)</label>
                <input type="text" name="name[en]" value="{{ old('name.en', $tag->getTranslation('name', 'en', false) ?? '') }}" class="form-control">
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>
@endsection

@extends('admin.layouts.app')

@section('header', isset($tag) ? 'Редактировать тег' : 'Создать тег')

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
    <form action="{{ isset($tag) ? route('admin.tags.update', $tag) : route('admin.tags.store') }}" method="POST">
        @csrf
        @if(isset($tag))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="form-label">Название тега</label>
            <input type="text" name="name" value="{{ old('name', $tag->name ?? '') }}" required class="form-control">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>
@endsection
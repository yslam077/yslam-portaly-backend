@extends('admin.layouts.app')

@section('header', 'Создать тег')

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width: 700px;">
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tags.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Название (TM) <span class="text-danger">*</span></label>
                <input type="text" name="name[tm]" value="{{ old('name.tm') }}" required
                    class="form-control @error('name.tm') is-invalid @enderror">
                @error('name.tm')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Название (RU) <span class="text-danger">*</span></label>
                <input type="text" name="name[ru]" value="{{ old('name.ru') }}" required
                    class="form-control @error('name.ru') is-invalid @enderror">
                @error('name.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Название (EN)</label>
                <input type="text" name="name[en]" value="{{ old('name.en') }}"
                    class="form-control @error('name.en') is-invalid @enderror">
                @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>
@endsection

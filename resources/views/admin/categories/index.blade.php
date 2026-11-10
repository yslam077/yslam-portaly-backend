@extends('admin.layouts.app')

@section('header', 'Управление категориями')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted">Heme kategoriýa: {{ $categories->count() }}</span>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Kategoriýa goşmak</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle m-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Ady(TM / RU)</th>
                    <th>Slag</th>
                    <th class="text-end">Deytwiya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        <div class="fw-bold">{{ $category->getTranslation('name', 'tm', false) }}</div>
                        <div class="text-muted small">{{ $category->getTranslation('name', 'ru', false) }}</div>
                    </td>
                    <td class="text-muted font-monospace">{{ $category->slug }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary me-1">Редактировать</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить категорию?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Категорий пока нет</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends('admin.layouts.app')

@section('header', 'Управление тегами')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted">Всего тегов: {{ $tags->count() }}</span>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">Добавить тег</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle m-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Слаг</th>
                    <th class="text-end">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                <tr>
                    <td>{{ $tag->id }}</td>
                    <td class="fw-semibold">{{ $tag->name }}</td>
                    <td class="text-muted font-monospace">{{ $tag->slug }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary me-1">Редактировать</a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить тег?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Тегов пока нет</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
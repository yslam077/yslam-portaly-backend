@extends('admin.layouts.app')

@section('header', 'Управление статьями')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold m-0">Список статей</h5>
    @if(in_array(auth()->user()->role, ['admin', 'editor', 'author']))
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary fw-semibold">
            + Добавить статью
        </a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">ID</th>
                        <th scope="col">Заголовок</th>
                        <th scope="col">Категория</th>
                        <th scope="col">Автор</th>
                        <th scope="col">Дата</th>
                        <th scope="col" class="text-end pe-3">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $article->id }}</td>
                            <td>{{ $article->title ?? 'Без заголовка' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $article->category->name ?? 'Без категории' }}
                                </span>
                            </td>
                            <td>{{ $article->user->name ?? 'N/A' }}</td>
                            <td>{{ $article->created_at?->format('d.m.Y H:i') }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary me-1">
                                    Редактировать
                                </a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить статью?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Статей пока нет.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $articles->links() }}
</div>
@endsection
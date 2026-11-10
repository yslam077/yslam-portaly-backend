@extends('admin.layouts.app')

@section('header', 'Upravleniye Stat\'yami')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold m-0">Spisok Stat'ey</h5>
    @if(in_array(auth()->user()->role, ['admin', 'editor', 'author']))
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary fw-semibold">
            + Dobavit' Stat'yu
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
                        <th scope="col">Zagolovok</th>
                        <th scope="col">Kategoriya</th>
                        <th scope="col">Avtor</th>
                        <th scope="col">Data</th>
                        <th scope="col" class="text-end pe-3">Deystviya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $article->id }}</td>
                            <td>{{ $article->title }}</td>
                            <td><span class="badge bg-info text-dark">{{ $article->category->name ?? 'Bez kategorii' }}</span></td>
                            <td>{{ $article->author->name ?? 'N/A' }}</td>
                            <td>{{ $article->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary me-1">
                                    Redaktirovat'
                                </a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Udalit\' stat\'yu?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Udalit'</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Stat'i poka ne dobavleny.</td>
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
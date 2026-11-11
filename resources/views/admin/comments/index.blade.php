@extends('admin.layouts.app')

@section('header', 'Модерация комментариев')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">ID</th>
                        <th scope="col">Автор</th>
                        <th scope="col">Комментарий</th>
                        <th scope="col">К статье</th>
                        <th scope="col">Статус</th>
                        <th scope="col" class="text-end pe-3">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $comment->id }}</td>
                            <td>{{ $comment->user->name ?? 'Гость' }}</td>
                            <td style="max-width: 300px;" class="text-truncate">{{ $comment->body }}</td>
                            <td><span class="text-muted small">{{ $comment->article->title ?? 'N/A' }}</span></td>
                            <td>
                                @if($comment->status === 'approved')
                                    <span class="badge bg-success">Одобрен</span>
                                @elseif($comment->status === 'rejected')
                                    <span class="badge bg-danger">Отклонён</span>
                                @else
                                    <span class="badge bg-warning text-dark">На проверке</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                @if($comment->status !== 'approved')
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Одобрить</button>
                                    </form>
                                @endif

                                @if($comment->status !== 'rejected')
                                    <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="d-inline me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">Отклонить</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить комментарий?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Комментариев для модерации нет.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $comments->links() }}
</div>
@endsection
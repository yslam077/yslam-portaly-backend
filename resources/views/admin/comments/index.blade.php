@extends('admin.layouts.app')

@section('header', 'Moderatsiya Kommentariev')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">ID</th>
                        <th scope="col">Avtor</th>
                        <th scope="col">Kommentariy</th>
                        <th scope="col">K Stat'ye</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end pe-3">Deystviya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $comment->id }}</td>
                            <td>{{ $comment->user_name ?? 'Gost\'' }}</td>
                            <td style="max-width: 300px;" class="text-truncate">{{ $comment->body }}</td>
                            <td><span class="text-muted small">{{ $comment->article->title ?? 'N/A' }}</span></td>
                            <td>
                                @if($comment->is_approved)
                                    <span class="badge bg-success">Odobren</span>
                                @else
                                    <span class="badge bg-warning text-dark">Na proverke</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                @if(!$comment->is_approved)
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Odobrit'</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="d-inline me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">Otklonit'</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Udalit\' kommentariy?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Udalit'</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Kommentariev dlya moderatsii net.</td>
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
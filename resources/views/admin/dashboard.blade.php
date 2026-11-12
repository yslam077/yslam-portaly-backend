@extends('admin.layouts.app')

@section('header', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-primary"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-primary">{{ $stats['articles_count'] }}</div>
                    <div class="text-muted small">Statya</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-success"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($stats['views_total']) }}</div>
                    <div class="text-muted small">Goruldi</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-warning"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-warning">{{ $stats['comments_count'] }}</div>
                    <div class="text-muted small">
                        Komment
                        @if($stats['pending_comments'] > 0)
                            <span class="badge bg-danger ms-1">{{ $stats['pending_comments'] }} new</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-info"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                </div>
                <div>
                    @if(auth()->user()->role === 'admin')
                        <div class="fs-4 fw-bold text-info">{{ $stats['users_count'] }}</div>
                        <div class="text-muted small">Ulanyjy</div>
                    @else
                        <div class="fs-4 fw-bold text-info">{{ $stats['categories_count'] }}</div>
                        <div class="text-muted small">Kategory</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Latest Articles --}}
    @if($latest_articles->count())
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-0 px-4">
                <h6 class="fw-bold mb-0">Soňky statýalar</h6>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-outline-primary">Hemmesi</a>
            </div>
            <div class="card-body px-4 pb-3">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="text-muted small">
                            <tr>
                                <th>Ady</th>
                                <th>Kategory</th>
                                <th>Status</th>
                                <th>Görülen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latest_articles as $article)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="text-decoration-none text-dark fw-medium">
                                        {{ Str::limit($article->title, 40) }}
                                    </a>
                                    <div class="text-muted small">{{ $article->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ $article->category?->name ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    @php $s = $article->status; @endphp
                                    <span class="badge {{ $s === 'published' ? 'bg-success' : ($s === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        {{ $s === 'published' ? 'Çap edildi' : ($s === 'pending' ? 'Garaşylýar' : 'Çernovik') }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ number_format($article->views) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Latest Comments --}}
    @if($latest_comments->count())
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-0 px-4">
                <h6 class="fw-bold mb-0">Soňky kommentarlar</h6>
                <a href="{{ route('admin.comments.index') }}" class="btn btn-sm btn-outline-primary">Hemmesi</a>
            </div>
            <div class="card-body px-4 pb-3">
                @foreach($latest_comments as $comment)
                <div class="border-bottom py-2 {{ $loop->last ? 'border-0' : '' }}">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-semibold small">{{ $comment->user?->name ?? 'Myhman' }}</span>
                        <span class="badge {{ $comment->status === 'approved' ? 'bg-success' : ($comment->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }} ms-2">
                            {{ $comment->status === 'approved' ? '✓' : ($comment->status === 'rejected' ? '✗' : '…') }}
                        </span>
                    </div>
                    <p class="mb-0 text-muted small">{{ Str::limit($comment->body, 70) }}</p>
                    <div class="text-muted" style="font-size:11px;">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Welcome card if nothing to show --}}
    @if(!$latest_articles->count() && !$latest_comments->count())
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 text-center">
            <div class="text-muted mb-2" style="font-size: 48px;">📋</div>
            <h5 class="fw-bold mb-2">Dolandyryş paneline hoş geldiňiz!</h5>
            <p class="text-muted mb-0">Statýa, kategoriýa, teg, banner we ulanyjylary dolandyrmak üçin çep menýuny ulanyň.</p>
        </div>
    </div>
    @endif
</div>

@endsection

@extends('admin.layouts.app')

@section('header', 'Banner dolandyrjy')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted">Reklam bannerlar we statyalar</span>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Banner gosmak</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle m-0">
            <thead class="table-light">
                <tr>
                    <th>Suratlar</th>
                    <th>Zagolowka</th>
                    <th>Pozisiya</th>
                    <th>Status</th>
                    <th class="text-end">Eden zady</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td>
                        @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="" class="rounded" style="width: 80px; height: 45px; object-fit: cover;">
                        @else
                            <span class="text-muted small">Surat yok</span>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $banner->title ?? 'Без заголовка' }}</td>
                    <td><span class="badge bg-secondary">{{ $banner->position }}</span></td>
                    <td>
                        @if($banner->is_active)
                            <span class="badge bg-success">Aktiwda</span>
                        @else
                            <span class="badge bg-secondary">Ocurmek</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline-primary me-1">Редактировать</a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить баннер?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Pozzmak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Banner suwagtlykca yok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
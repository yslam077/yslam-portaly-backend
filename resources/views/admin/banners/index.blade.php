@extends('admin.layouts.app')

@section('header', 'Управление баннерами')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted">Рекламные баннеры и слайдеры</span>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Добавить баннер</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle m-0">
            <thead class="table-light">
                <tr>
                    <th>Изображение</th>
                    <th>Заголовок</th>
                    <th>Позиция</th>
                    <th>Статус</th>
                    <th class="text-end">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td>
                        @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="" class="rounded" style="width: 80px; height: 45px; object-fit: cover;">
                        @else
                            <span class="text-muted small">Нет фото</span>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $banner->title ?? 'Без заголовка' }}</td>
                    <td><span class="badge bg-secondary">{{ $banner->position }}</span></td>
                    <td>
                        @if($banner->is_active)
                            <span class="badge bg-success">Активен</span>
                        @else
                            <span class="badge bg-secondary">Отключен</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline-primary me-1">Редактировать</a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить баннер?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Баннеров пока нет</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
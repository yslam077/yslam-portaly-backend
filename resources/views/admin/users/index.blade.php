@extends('admin.layouts.app')

@section('header', 'Управление пользователями')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted">Администраторы и сотрудники портала</span>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Добавить пользователя</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle m-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Дата регистрации</th>
                    <th class="text-end">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }} text-uppercase">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $user->created_at?->format('d.m.Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1">Редактировать</a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить пользователя?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Пользователей нет</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
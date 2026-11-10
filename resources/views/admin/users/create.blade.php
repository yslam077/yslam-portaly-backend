@extends('admin.layouts.app')

@section('header', isset($user) ? 'Редактировать пользователя' : 'Создать пользователя')

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Имя</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Пароль {{ isset($user) ? '(оставьте пустым, если не меняется)' : '' }}</label>
            <input type="password" name="password" {{ isset($user) ? '' : 'required' }} class="form-control">
        </div>

        <div class="mb-4">
            <label class="form-label">Роль</label>
            <select name="role" class="form-select">
                <option value="moderator" {{ (old('role', $user->role ?? '') === 'moderator') ? 'selected' : '' }}>Модератор</option>
                <option value="admin" {{ (old('role', $user->role ?? '') === 'admin') ? 'selected' : '' }}>Администратор</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>
@endsection
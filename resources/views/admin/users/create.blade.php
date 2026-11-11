@extends('admin.layouts.app')

@section('header', 'Создать пользователя')

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width: 700px;">
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Имя <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Имя пользователя (Логин) <span class="text-danger">*</span></label>
                <input type="text" name="username" value="{{ old('username') }}" required minlength="3" maxlength="50" pattern="[a-zA-Z0-9._-]+" autocomplete="username" class="form-control @error('username') is-invalid @enderror">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Роль <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" id="roleSelect" onchange="togglePasswordField()">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Полный доступ)</option>
                    <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor (Редактор)</option>
                    <option value="author" {{ old('role') === 'author' ? 'selected' : '' }}>Author (Автор новостей)</option>
                    <option value="moderator" {{ old('role') === 'moderator' ? 'selected' : '' }}>Moderator (Модератор комментариев)</option>
                    <option value="reader" {{ old('role') === 'reader' ? 'selected' : '' }}>Reader (Читатель - только сайт)</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text text-muted small mt-1">Роли Admin, Editor, Author, Moderator имеют доступ к панели управления по логину и паролю. Читатели (Reader) авторизуются на сайте через OTP.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Телефон</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+993..." class="form-control @error('phone') is-invalid @enderror">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-12" id="passwordGroup">
                <label class="form-label">Пароль для входа в панель <span class="text-danger">*</span></label>
                <input type="password" name="password" minlength="6" class="form-control @error('password') is-invalid @enderror" placeholder="Введите надёжный пароль">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>

<script>
function togglePasswordField() {
    const role = document.getElementById('roleSelect').value;
    const pwdGroup = document.getElementById('passwordGroup');
    if (role === 'reader') {
        pwdGroup.style.display = 'none';
    } else {
        pwdGroup.style.display = 'block';
    }
}
togglePasswordField();
</script>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход — Yslam Portaly Admin</title>

    <!-- Локальный Bootstrap 5 -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

    <div class="card border-0 shadow-sm p-4" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <h3 class="fw-bold m-0">Yslam Portaly</h3>
            <span class="text-muted small">Панель администратора</span>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-control" autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input type="password" name="password" required class="form-control">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Запомнить меня</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-semibold">Войти</button>
        </form>
    </div>

</body>
</html>
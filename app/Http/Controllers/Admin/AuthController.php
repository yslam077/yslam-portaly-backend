<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function loginWithPassword(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ]);
        $username = strtolower(trim($data['username']));

        $key = 'admin-login:' . $username . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()
                ->withErrors(['username' => 'Слишком много попыток. Повторите через ' . RateLimiter::availableIn($key) . ' сек.'])
                ->onlyInput('username');
        }

        $user = User::where('username', $username)->first();

        if (! $user || ! $user->isStaff() || ! Hash::check($data['password'], (string) $user->password)) {
            RateLimiter::hit($key, 60);

            return back()->withErrors(['username' => 'Неверное имя пользователя или пароль.'])->onlyInput('username');
        }

        RateLimiter::clear($key);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
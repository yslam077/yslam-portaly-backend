<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Sms\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AdminAuthController extends Controller
{
    public function __construct(protected SmsService $smsService) {}

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function loginPassword(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if (!in_array($user->role, ['admin', 'editor', 'author', 'moderator'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'У вас нет доступа к административной панели.']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Неверный логин или пароль.']);
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => ['required', 'string']]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user || !in_array($user->role, ['admin', 'editor', 'author', 'moderator'])) {
            return response()->json(['message' => 'Сотрудник с таким телефоном не найден.'], 404);
        }

        $code = (string) random_int(100000, 999999);
        Cache::put("admin_otp_{$request->phone}", Hash::make($code), now()->addMinutes(5));

        $this->smsService->send($request->phone, "Код входа в админ-панель Yslam Portaly: {$code}");

        return response()->json(['message' => 'Код отправлен.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $hashedCode = Cache::get("admin_otp_{$request->phone}");

        if (!$hashedCode || !Hash::check($request->code, $hashedCode)) {
            return response()->json(['message' => 'Неверный код или срок его действия истек.'], 422);
        }

        Cache::forget("admin_otp_{$request->phone}");

        $user = User::where('phone', $request->phone)->firstOrFail();
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['redirect' => route('admin.dashboard')]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
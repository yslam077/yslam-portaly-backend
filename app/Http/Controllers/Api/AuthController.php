<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Request OTP by Name (and optional phone)
     * Shows OTP in the response for test environment!
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
        ]);

        $name = trim($data['name']);
        $cacheKey = 'otp_user_' . Str::slug($name);

        $key = 'api-otp:' . Str::slug($name) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'message' => 'Ýygy-ýygydan synanyşyk edildi. Soňrak synanyşyň.',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }
        RateLimiter::hit($key, 60);

        // Generate 4-digit or 6-digit OTP code (e.g. 4 digits: 1000-9999 or 6 digits)
        $code = (string) random_int(1000, 9999);
        Cache::put($cacheKey, Hash::make($code), now()->addMinutes(10));

        return response()->json([
            'message' => 'Tassyklama kody ugradyldy.',
            'code' => $code, // Displayed because this is a test site
            'name' => $name,
        ]);
    }

    /**
     * Verify OTP and Login / Auto-register reader
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:25'],
        ]);

        $name = trim($data['name']);
        $cacheKey = 'otp_user_' . Str::slug($name);

        $hash = Cache::get($cacheKey);

        if (! $hash || ! Hash::check($data['code'], $hash)) {
            return response()->json([
                'message' => 'Nädogry tassyklama kody ýa-da möhleti gutaran.',
            ], 422);
        }

        // Clean cache after successful verification
        Cache::forget($cacheKey);

        // Find or create user with role 'reader'
        $user = User::where('name', $name)->first();

        if (! $user) {
            $baseUsername = Str::slug($name);
            if (empty($baseUsername)) {
                $baseUsername = 'reader';
            }
            $username = $baseUsername . '-' . random_int(100, 999);

            $phone = !empty($data['phone']) ? $data['phone'] : '+993' . random_int(60000000, 65999999);
            // Ensure unique phone if randomly generated
            while (User::where('phone', $phone)->exists()) {
                $phone = '+993' . random_int(60000000, 65999999);
            }

            $user = User::create([
                'name' => $name,
                'username' => $username,
                'phone' => $phone,
                'role' => 'reader',
                'phone_verified_at' => now(),
            ]);
        }

        // Generate Sanctum token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Üstünlikli girildi.',
            'token' => $token,
            'user' => new AuthUserResource($user),
        ]);
    }

    public function login(Request $request)
    {
        // Support legacy or forward to OTP
        return $this->verifyOtp($request);
    }

    public function register(Request $request)
    {
        return $this->verifyOtp($request);
    }

    public function me(Request $request): AuthUserResource
    {
        return new AuthUserResource($request->user());
    }

    public function logout(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        Auth::guard('web')->logout();

        return response()->json([
            'message' => 'Üstünlikli çykyldy.',
        ]);
    }
}

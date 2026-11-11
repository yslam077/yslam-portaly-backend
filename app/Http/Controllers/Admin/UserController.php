<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected array $roles = ['admin', 'editor', 'author', 'moderator', 'reader'];

    public function index(Request $request)
    {
        $query = User::query();

        if ($search = trim((string) $request->input('search'))) {
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) . '%';
            $query->where(fn ($q) => $q
                ->where('name', 'ILIKE', $like)
                ->orWhere('username', 'ILIKE', $like)
                ->orWhere('phone', 'ILIKE', $like));
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->merge(['username' => Str::lower(trim((string) $request->input('username')))]);

        $role = $request->input('role');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9._-]+$/', 'unique:users,username'],
            'phone' => ['nullable', 'string', 'unique:users,phone'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => [Rule::requiredIf(fn () => $role !== 'reader'), 'nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in($this->roles)],
        ]);

        if (empty($validated['phone'])) {
            $validated['phone'] = '+993' . random_int(60000000, 65999999);
            while (User::where('phone', $validated['phone'])->exists()) {
                $validated['phone'] = '+993' . random_int(60000000, 65999999);
            }
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $validated['phone_verified_at'] = now();

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно создан.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->merge(['username' => Str::lower(trim((string) $request->input('username')))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9._-]+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'phone' => ['nullable', 'string', Rule::unique('users', 'phone')->ignore($user->id)],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in($this->roles)],
        ]);

        if ($validated['role'] !== $user->role) {
            if ($user->id === $request->user()->id) {
                return back()->withInput()->with('error', 'Нельзя менять свою собственную роль.');
            }
            if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
                return back()->withInput()->with('error', 'Нельзя снять роль с последнего администратора.');
            }
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь обновлён.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Нельзя удалить самого себя.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Нельзя удалить последнего администратора.');
        }

        if ($user->articles()->withTrashed()->exists()) {
            return back()->with('error', 'Нельзя удалить пользователя, у которого есть новости.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Пользователь удалён.');
    }
}

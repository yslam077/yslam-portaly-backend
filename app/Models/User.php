<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'phone_verified_at',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'editor', 'author', 'moderator']);
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : explode(',', $roles);
        return in_array($this->role, $roles);
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = config("permissions.roles.{$this->role}", []);

        if (in_array('*', $permissions)) {
            return true;
        }

        return in_array($permission, $permissions);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
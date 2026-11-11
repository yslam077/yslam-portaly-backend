<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CreateAdminCommand extends Command
{
    protected $signature = 'make:admin';
    protected $description = 'Create a new administrator account';

    public function handle(): int
    {
        $name = $this->ask('Enter admin name');
        $username = Str::lower(trim((string) $this->ask('Enter admin username')));
        $password = $this->secret('Enter admin password');

        $validator = Validator::make([
            'name' => $name,
            'username' => $username,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9._-]+$/', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'username' => $username,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->info("Administrator account '{$username}' successfully created!");
        return self::SUCCESS;
    }
}
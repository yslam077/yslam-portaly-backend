<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'make:admin';
    protected $description = 'Create a new administrator account';

    public function handle(): int
    {
        $name = $this->ask('Enter admin name');
        $phone = $this->ask('Enter admin phone number');
        $password = $this->secret('Enter admin password');

        $validator = Validator::make([
            'name' => $name,
            'phone' => $phone,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'phone' => $phone,
            'password' => Hash::make($password),
            'role' => 'admin',
            'phone_verified_at' => now(),
        ]);

        $this->info('Administrator account successfully created!');
        return self::SUCCESS;
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'yslam_portal_admin077'],
            [
                'name' => 'yslam_portal_admin077',
                'password' => Hash::make('yslam_shop_admin077'),
                'role' => 'admin',
                'email' => 'admin077@yslam.tm',
                'phone' => '+99360000077',
                'phone_verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin user for the application.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@realestate.com',
            'password'          => 'password123',
            'is_admin'          => true,
            'email_verified_at' => now(),
        ]);
    }
}

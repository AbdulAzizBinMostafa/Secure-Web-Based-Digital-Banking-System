<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'System Super Admin',
            'email' => 'admin@diubank.com',
            'password' => Hash::make('Admin@1234'),
            'role' => 'admin',
            'is_mfa_verified' => true, // Bypass OTP for initial admin setup
        ]);
    }
}
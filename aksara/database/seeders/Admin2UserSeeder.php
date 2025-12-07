<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Admin2UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin2 already exists
        $existingAdmin = User::where('email', 'admin2@aksara.com')->first();
        
        if ($existingAdmin) {
            $this->command->info('Admin2 user already exists!');
            return;
        }

        User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@aksara.com',
            'password' => Hash::make('password123'), // Change this to your preferred password
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin2 user created successfully!');
        $this->command->info('Email: admin2@aksara.com');
        $this->command->info('Password: password123');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::factory()->count(10)->create(); 

        User::create([
            'name' => 'John Doe',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'password' => Hash::make('password123'), 
            'last_seen' => now(),
            'role' => '0',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'user@example.com',
            'phone' => '0987654321',
            'password' => Hash::make('password123'), 
            'last_seen' => now(),
            'role' => '1',
        ]);

        User::create([
            'name' => 'Alice Johnson',
            'email' => 'agent@example.com',
            'phone' => '5551234567',
            'password' => Hash::make('password123'), 
            'last_seen' => now(),
            'role' => '2',
        ]);
    }
}

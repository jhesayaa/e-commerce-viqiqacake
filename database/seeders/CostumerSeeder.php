<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
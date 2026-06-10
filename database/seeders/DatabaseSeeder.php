<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
        * Seed the application's database.
        */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@agrimessage.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Penyuluh
        User::updateOrCreate(
            ['email' => 'penyuluh@agrimessage.com'],
            [
                'name' => 'Budi Penyuluh',
                'password' => Hash::make('password'),
                'role' => 'penyuluh',
            ]
        );
    }
}

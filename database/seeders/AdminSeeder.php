<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@disnakerlebak.go.id'],
            [
                'name' => 'Super Admin Disnaker',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );
    }
}

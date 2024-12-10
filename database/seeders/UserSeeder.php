<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
Use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'tamu',
            'email' => 'tamu',
            'password' => Hash::make('tamu'),
            'role' => 'GUEST'
        ]);

        User::create([
            'name' => 'staf',
            'email' => 'staf',
            'password' => Hash::make('staf'),
            'role' => 'STAFF'
        ]);

        User::create([
            'name' => 'hed',
            'email' => 'hed',
            'password' => Hash::make('hed'),
            'role' => 'HEAD_STAFF'
        ]);
    }
}

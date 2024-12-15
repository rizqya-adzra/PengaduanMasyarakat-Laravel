<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StaffProvince;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'tamu',
            'email' => 'tamu@gmail.com',
            'password' => Hash::make('tamu'),
            'role' => 'GUEST'
        ]);

        User::create([
            'name' => 'staf',
            'email' => 'staf@gmail.com',
            'password' => Hash::make('staf'),
            'role' => 'STAFF'
        ]);

        $headStaff = User::create([
            'name' => 'head',
            'email' => 'head@gmail.com',
            'password' => Hash::make('head'),
            'role' => 'HEAD_STAFF'
        ]);

        StaffProvince::create([
            'user_id' => $headStaff->id,
            'province' => 'JAWA BARAT',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

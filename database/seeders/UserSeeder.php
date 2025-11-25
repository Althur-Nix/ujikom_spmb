<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Keuangan',
                'email' => 'keuangan@test.com',
                'password' => Hash::make('keuangan123'),
                'role' => 'keuangan'
            ],
            [
                'name' => 'Panitia',
                'email' => 'panitia@test.com',
                'password' => Hash::make('panitia123'),
                'role' => 'panitia'
            ],
            [
                'name' => 'Siswa',
                'email' => 'siswa@test.com',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa'
            ],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@test.com',
                'password' => Hash::make('kepsek123'),
                'role' => 'kepala_sekolah'
            ]
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
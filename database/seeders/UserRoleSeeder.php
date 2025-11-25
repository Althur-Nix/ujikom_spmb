<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@spmb.test',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ],
            [
                'name' => 'Panitia SPMB',
                'email' => 'panitia@spmb.test',
                'password' => Hash::make('password'),
                'role' => 'panitia'
            ],
            [
                'name' => 'Staff Keuangan',
                'email' => 'keuangan@spmb.test',
                'password' => Hash::make('password'),
                'role' => 'keuangan'
            ],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@spmb.test',
                'password' => Hash::make('password'),
                'role' => 'kepala_sekolah'
            ],
            [
                'name' => 'Calon Siswa',
                'email' => 'siswa@spmb.test',
                'password' => Hash::make('password'),
                'role' => 'pendaftar'
            ]
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
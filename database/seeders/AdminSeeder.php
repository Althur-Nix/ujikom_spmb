<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@spmb.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Verifikator Admin',
            'email' => 'verifikator@spmb.test',
            'password' => Hash::make('verifikator123'),
            'role' => 'verifikator_adm',
        ]);

        User::create([
            'name' => 'Staff Keuangan',
            'email' => 'keuangan@spmb.test',
            'password' => Hash::make('keuangan123'),
            'role' => 'keuangan',
        ]);

        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@spmb.test',
            'password' => Hash::make('kepsek123'),
            'role' => 'kepsek',
        ]);
    }
}
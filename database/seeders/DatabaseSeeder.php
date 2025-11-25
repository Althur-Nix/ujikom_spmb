<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Buat user kepala sekolah untuk testing
        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@sekolah.com',
            'password' => bcrypt('password'),
            'role' => 'kepala_sekolah'
        ]);

        // Buat user admin untuk testing
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sekolah.com', 
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
    }
}

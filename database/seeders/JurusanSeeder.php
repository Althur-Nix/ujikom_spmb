<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['kode'=>'PPLG','nama'=>'PPLG','kuota'=>120,'aktif'=>1],
            ['kode'=>'AKT','nama'=>'Akuntansi','kuota'=>60,'aktif'=>1],
            ['kode'=>'ANI','nama'=>'Animasi','kuota'=>40,'aktif'=>1],
            ['kode'=>'DKV','nama'=>'DKV','kuota'=>50,'aktif'=>1],
            ['kode'=>'PEM','nama'=>'Pemasaran','kuota'=>50,'aktif'=>1],
        ];

        foreach ($items as $it) {
            Jurusan::updateOrCreate(['kode'=>$it['kode']], $it);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jurusan')->insert([
            [ 'kode' => 'TI',  'nama' => 'Teknik Informatika' ],
            [ 'kode' => 'SI',  'nama' => 'Sistem Informasi' ],
            [ 'kode' => 'TM',  'nama' => 'Teknik Mesin' ],
            [ 'kode' => 'TE',  'nama' => 'Teknik Elektro' ],
            [ 'kode' => 'TS',  'nama' => 'Teknik Sipil' ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('instansi')->insert([
            [
                'nama' => 'Politeknik Negeri Jakarta',
                'alamat' => 'Jl. Prof Dr G.A Siwabessy, Depok',
                'email' => 'info@pnj.ac.id',
                'telepon' => '021-7270036',
            ],
            [
                'nama' => 'Institut Teknologi Bandung',
                'alamat' => 'Jl. Ganesha No.10, Bandung',
                'email' => 'info@itb.ac.id',
                'telepon' => '022-2500935',
            ],
            [
                'nama' => 'Universitas Indonesia',
                'alamat' => 'Kampus UI Depok',
                'email' => 'contact@ui.ac.id',
                'telepon' => '021-788491',
            ],
        ]);
    }
}

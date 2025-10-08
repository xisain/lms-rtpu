<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\material;
use App\Models\submaterial;

class SubMaterialSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $materials = material::all();

        if ($materials->isEmpty()) {
            $this->command->warn('Belum ada material, jalankan MaterialSeeders dulu!');
            return;
        }

        foreach ($materials as $material) {
            $submaterials = [
                [
                    'material_id' => $material->id,
                    'nama_submateri' => 'Pengenalan ' . $material->nama_materi,
                    'type' => 'text',
                    'isi_materi' => 'Penjelasan dasar mengenai ' . $material->nama_materi,
                ],
                [
                    'material_id' => $material->id,
                    'nama_submateri' => 'Contoh ' . $material->nama_materi,
                    'type' => 'video',
                    'isi_materi' => 'Link video pembelajaran ' . $material->nama_materi,
                ],
                [
                    'material_id' => $material->id,
                    'nama_submateri' => 'Latihan ' . $material->nama_materi,
                    'type' => 'pdf',
                    'isi_materi' => 'File latihan untuk ' . $material->nama_materi,
                ],
            ];

            submaterial::insert($submaterials);
        }
    }
}

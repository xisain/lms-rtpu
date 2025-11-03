<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        category::create([
            'category' => 'Pelatihan',
            'description'=> 'Pelatihan ini diselenggarakan oleh Politeknik Negeri Jakarta (PNJ) sebagai upaya untuk meningkatkan kompetensi dan keterampilan peserta di bidang yang relevan dengan perkembangan industri saat ini. Melalui kegiatan ini, peserta dibekali dengan pengetahuan teoritis, praktik langsung, dan studi kasus nyata, sehingga mampu menerapkan hasil pelatihan dalam dunia kerja maupun pengembangan karier. Program ini juga menjadi wadah untuk memperluas jejaring profesional serta menumbuhkan semangat inovasi dan profesionalisme di kalangan mahasiswa maupun masyarakat umum.',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\material;
use App\Models\submaterial;

class SubMaterialSeeders extends Seeder
{
    public function run(): void
    {
        $materials = Material::all();

        if ($materials->isEmpty()) {
            $this->command->warn('Belum ada material, jalankan MaterialSeeders dulu!');
            return;
        }

        foreach ($materials as $material) {
            $submaterials = $this->generateSubMaterials($material);

            foreach ($submaterials as $index => $submaterial) {
                SubMaterial::create([
                    'material_id' => $material->id,
                    'nama_submateri' => $submaterial['nama'],
                    'type' => $submaterial['type'],
                    'isi_materi' => $submaterial['content'],
                ]);
            }
        }
    }

    private function generateSubMaterials($material)
    {
        $materialName = $material->nama_materi;

        // Pattern untuk submateri yang realistis - hanya 3 tipe: text, video, pdf
        return [
            [
                'nama' => 'Pengantar ' . $materialName,
                'type' => 'text',
                'content' => $this->generateTextContent($materialName, 'pengantar'),
            ],
            [
                'nama' => 'Video Tutorial: ' . $materialName,
                'type' => 'video',
                'content' => 'https://www.youtube.com/watch?v=' . $this->generateRandomYoutubeId(),
            ],
            [
                'nama' => 'Materi Lengkap: ' . $materialName,
                'type' => 'text',
                'content' => $this->generateTextContent($materialName, 'lengkap'),
            ],
            [
                'nama' => 'Slide Presentasi',
                'type' => 'pdf',
                'content' => 'course/pdf/slide.pdf'
            ],
            [
                'nama' => 'Video Praktik & Studi Kasus',
                'type' => 'video',
                'content' => 'https://www.youtube.com/watch?v=' . $this->generateRandomYoutubeId(),
            ],
            [
                'nama' => 'Latihan & Exercise',
                'type' => 'text',
                'content' => $this->generatePracticeContent($materialName),
            ],
            [
                'nama' => 'Handout & Cheat Sheet',
                'type' => 'pdf',
                'content' => 'course/pdf/handout.pdf'
            ],
            [
                'nama' => 'Referensi & Sumber Belajar',
                'type' => 'text',
                'content' => $this->generateReferenceContent($materialName),
            ],
        ];
    }

    private function generateRandomYoutubeId()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
        $id = '';
        for ($i = 0; $i < 11; $i++) {
            $id .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $id;
    }

    private function generateTextContent($materialName, $type = 'lengkap')
    {
        if ($type === 'pengantar') {
            return <<<HTML
<div class="content-wrapper">
    <h2>Pengantar: {$materialName}</h2>

    <h3>Selamat Datang!</h3>
    <p>
        Selamat datang di materi <strong>{$materialName}</strong>. Pada bagian ini, Anda akan
        mempelajari konsep-konsep penting yang akan membantu Anda memahami topik ini secara menyeluruh.
    </p>

    <h3>Apa yang Akan Dipelajari?</h3>
    <ul>
        <li>Konsep dasar dan fundamental dari {$materialName}</li>
        <li>Penerapan praktis dalam dunia nyata</li>
        <li>Best practices dan common patterns</li>
        <li>Tips dan trik untuk optimasi</li>
    </ul>

    <h3>Prasyarat</h3>
    <p>
        Untuk mengikuti materi ini dengan baik, pastikan Anda sudah memahami materi-materi
        sebelumnya dan siap untuk belajar hal baru.
    </p>

    <h3>Durasi Belajar</h3>
    <p>
        Estimasi waktu untuk menyelesaikan materi ini adalah 2-3 jam, termasuk latihan dan praktik.
        Jangan terburu-buru, pahami setiap konsep dengan baik.
    </p>
</div>
HTML;
        }

        return <<<HTML
<div class="content-wrapper">
    <h2>{$materialName}</h2>

    <h3>Tujuan Pembelajaran</h3>
    <ul>
        <li>Memahami konsep dasar dari {$materialName}</li>
        <li>Mampu mengimplementasikan dalam project nyata</li>
        <li>Mengetahui best practices dan common pitfalls</li>
        <li>Siap untuk melanjutkan ke materi selanjutnya</li>
    </ul>

    <h3>Pembahasan Materi</h3>
    <p>
        Pada materi ini, kita akan mempelajari {$materialName} secara mendalam.
        Materi ini merupakan bagian penting dalam perjalanan belajar Anda dan akan
        memberikan fondasi yang kuat untuk topik-topik selanjutnya.
    </p>

    <h3>Konsep Utama</h3>
    <p>
        {$materialName} adalah salah satu topik fundamental yang perlu dikuasai oleh
        setiap developer. Dalam praktiknya, pemahaman yang baik tentang materi ini
        akan membantu Anda dalam menyelesaikan berbagai masalah di dunia nyata.
    </p>

    <h3>Contoh Implementasi</h3>
    <p>
        Mari kita lihat bagaimana konsep ini diterapkan dalam praktek. Berikut adalah
        beberapa contoh penggunaan yang umum dijumpai dalam industri:
    </p>

    <pre><code>
// Contoh implementasi
function example() {
    // Code example here
    console.log("Learning {$materialName}");

    // Best practice implementation
    return processData();
}
    </code></pre>

    <h3>Best Practices</h3>
    <ul>
        <li>Selalu ikuti standar industri yang berlaku</li>
        <li>Tulis code yang clean dan maintainable</li>
        <li>Dokumentasikan code Anda dengan baik</li>
        <li>Lakukan testing secara menyeluruh</li>
        <li>Perhatikan performance dan scalability</li>
    </ul>

    <h3>Common Mistakes</h3>
    <p>
        Beberapa kesalahan umum yang sering dilakukan pemula adalah:
    </p>
    <ul>
        <li>Tidak memahami konsep dasar dengan baik</li>
        <li>Skip langkah-langkah penting dalam implementasi</li>
        <li>Tidak melakukan praktik secara konsisten</li>
        <li>Mengabaikan error handling dan edge cases</li>
    </ul>

    <h3>Tips Belajar</h3>
    <p>
        Untuk menguasai materi ini dengan baik:
    </p>
    <ul>
        <li>Jangan hanya membaca, praktikkan langsung</li>
        <li>Buat catatan untuk konsep-konsep penting</li>
        <li>Kerjakan semua latihan yang disediakan</li>
        <li>Jangan ragu bertanya jika ada yang kurang jelas</li>
        <li>Review materi secara berkala</li>
    </ul>

    <h3>Kesimpulan</h3>
    <p>
        {$materialName} adalah topik penting yang memerlukan pemahaman dan praktik yang baik.
        Pastikan Anda mengerjakan latihan yang disediakan dan jangan ragu untuk bertanya
        jika ada hal yang kurang jelas. Selamat belajar!
    </p>
</div>
HTML;
    }

    private function generatePracticeContent($materialName)
    {
        return <<<HTML
<div class="practice-content">
    <h2>Latihan: {$materialName}</h2>

    <div class="exercise">
        <h3>Latihan 1: Pemahaman Konsep</h3>
        <p><strong>Tujuan:</strong> Menguji pemahaman dasar tentang {$materialName}</p>
        <p><strong>Instruksi:</strong></p>
        <ol>
            <li>Buat implementasi sederhana dari konsep yang telah dipelajari</li>
            <li>Dokumentasikan code Anda dengan comment yang jelas</li>
            <li>Test implementasi Anda dengan berbagai input</li>
        </ol>
        <p><strong>Estimasi Waktu:</strong> 15-20 menit</p>
    </div>

    <div class="exercise">
        <h3>Latihan 2: Implementasi Real-world</h3>
        <p><strong>Tujuan:</strong> Mengaplikasikan {$materialName} dalam kasus nyata</p>
        <p><strong>Skenario:</strong></p>
        <p>
            Anda diminta untuk membangun fitur yang menggunakan konsep dari {$materialName}.
            Implementasikan solusi yang efisien dan scalable.
        </p>
        <p><strong>Kriteria:</strong></p>
        <ul>
            <li>Code harus clean dan readable</li>
            <li>Implementasi harus mengikuti best practices</li>
            <li>Include error handling</li>
            <li>Tambahkan unit tests</li>
        </ul>
        <p><strong>Estimasi Waktu:</strong> 30-45 menit</p>
    </div>

    <div class="exercise">
        <h3>Latihan 3: Challenge (Opsional)</h3>
        <p><strong>Tujuan:</strong> Mengasah kemampuan problem solving</p>
        <p><strong>Challenge:</strong></p>
        <p>
            Optimasi implementasi Anda dari latihan sebelumnya. Fokus pada performance,
            maintainability, dan scalability. Bandingkan dengan solusi awal Anda.
        </p>
        <p><strong>Estimasi Waktu:</strong> 45-60 menit</p>
    </div>

    <div class="submission">
        <h3>Pengumpulan</h3>
        <p>Submit hasil latihan Anda melalui GitHub repository atau zip file yang berisi:</p>
        <ul>
            <li>Source code lengkap</li>
            <li>README.md dengan penjelasan</li>
            <li>Screenshot hasil running program</li>
            <li>Refleksi singkat tentang learning process Anda</li>
        </ul>
    </div>
</div>
HTML;
    }

    private function generateReferenceContent($materialName)
    {
        return <<<HTML
<div class="reference-content">
    <h2>Referensi Tambahan: {$materialName}</h2>

    <h3>Dokumentasi Resmi</h3>
    <ul>
        <li><a href="#" target="_blank">Official Documentation</a> - Dokumentasi lengkap dan terpercaya</li>
        <li><a href="#" target="_blank">API Reference</a> - Reference untuk semua API yang tersedia</li>
        <li><a href="#" target="_blank">Best Practices Guide</a> - Panduan best practices dari official team</li>
    </ul>

    <h3>Tutorial & Articles</h3>
    <ul>
        <li><a href="#" target="_blank">Medium: Advanced Guide to {$materialName}</a></li>
        <li><a href="#" target="_blank">Dev.to: Tips and Tricks</a></li>
        <li><a href="#" target="_blank">FreeCodeCamp: Complete Tutorial</a></li>
    </ul>

    <h3>Video Resources</h3>
    <ul>
        <li><a href="#" target="_blank">YouTube: In-depth Tutorial Series</a></li>
        <li><a href="#" target="_blank">Udemy: Comprehensive Course (Paid)</a></li>
        <li><a href="#" target="_blank">Coursera: Certification Course</a></li>
    </ul>

    <h3>Tools & Libraries</h3>
    <ul>
        <li><a href="#" target="_blank">GitHub Awesome List</a> - Curated list of resources</li>
        <li><a href="#" target="_blank">NPM Packages</a> - Useful packages for implementation</li>
        <li><a href="#" target="_blank">VS Code Extensions</a> - Extensions to boost productivity</li>
    </ul>

    <h3>Community</h3>
    <ul>
        <li><a href="#" target="_blank">Stack Overflow Tag</a> - Q&A community</li>
        <li><a href="#" target="_blank">Discord Server</a> - Real-time discussion</li>
        <li><a href="#" target="_blank">Reddit Community</a> - News and discussions</li>
    </ul>

    <h3>Practice Platforms</h3>
    <ul>
        <li><a href="#" target="_blank">LeetCode</a> - Coding challenges</li>
        <li><a href="#" target="_blank">HackerRank</a> - Practice problems</li>
        <li><a href="#" target="_blank">CodeWars</a> - Gamified learning</li>
    </ul>

    <div class="tips">
        <h3>Tips Belajar</h3>
        <p>
            Jangan hanya membaca atau menonton tutorial. Praktik langsung dan buat project
            sendiri adalah cara terbaik untuk menguasai {$materialName}. Mulai dari project
            sederhana dan tingkatkan kompleksitasnya secara bertahap.
        </p>
    </div>
</div>
HTML;
    }
}

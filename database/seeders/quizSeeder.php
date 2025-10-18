<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Material;
use App\Models\quiz_option;
use App\Models\quiz_question;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $materials = Material::all();

        if ($materials->isEmpty()) {
            $this->command->warn('Belum ada material, jalankan MaterialSeeders dulu!');
            return;
        }

        foreach ($materials as $material) {
            // Buat quiz untuk setiap material
            $quiz = Quiz::create([
                'material_id' => $material->id,
                'judul_quiz' => 'Quiz: ' . $material->nama_materi,
                'is_required' => (bool) rand(0, 1),
            ]);

            // Generate pertanyaan berdasarkan nama material
            $questions = $this->generateQuestions($material->nama_materi);

            foreach ($questions as $questionData) {
                $question = quiz_question::create([
                    'quiz_id' => $quiz->id,
                    'pertanyaan' => $questionData['question'],
                ]);

                // Buat opsi jawaban
                foreach ($questionData['options'] as $option) {
                    quiz_option::create([
                        'quiz_question_id' => $question->id,
                        'teks_pilihan' => $option['text'],
                        'is_correct' => $option['is_correct'],
                    ]);
                }
            }
        }
    }

    private function generateQuestions($materialName)
    {
        // Database pertanyaan yang disesuaikan dengan jenis materi
        $questionTemplates = $this->getQuestionTemplates($materialName);

        // Ambil 8-12 pertanyaan secara random
        $numQuestions = rand(8, 12);
        $selectedQuestions = [];

        if (count($questionTemplates) <= $numQuestions) {
            $selectedQuestions = $questionTemplates;
        } else {
            $keys = array_rand($questionTemplates, $numQuestions);
            foreach ($keys as $key) {
                $selectedQuestions[] = $questionTemplates[$key];
            }
        }

        return $selectedQuestions;
    }

    private function getQuestionTemplates($materialName)
    {
        // Pertanyaan umum yang applicable untuk berbagai materi
        $generalQuestions = [
            [
                'question' => "Apa tujuan utama mempelajari {$materialName}?",
                'options' => [
                    ['text' => 'Untuk memahami konsep fundamental dan dapat mengimplementasikannya', 'is_correct' => true],
                    ['text' => 'Hanya untuk mendapat sertifikat', 'is_correct' => false],
                    ['text' => 'Untuk menghafal syntax dan code', 'is_correct' => false],
                    ['text' => 'Tidak ada tujuan spesifik', 'is_correct' => false],
                ],
            ],
            [
                'question' => "Manakah yang merupakan best practice dalam {$materialName}?",
                'options' => [
                    ['text' => 'Menulis code yang clean, readable, dan well-documented', 'is_correct' => true],
                    ['text' => 'Menulis code secepat mungkin tanpa dokumentasi', 'is_correct' => false],
                    ['text' => 'Copy-paste code tanpa memahami', 'is_correct' => false],
                    ['text' => 'Mengabaikan error handling', 'is_correct' => false],
                ],
            ],
            [
                'question' => "Apa yang harus dilakukan jika menemui error saat implementasi {$materialName}?",
                'options' => [
                    ['text' => 'Baca error message, cari di dokumentasi, dan debugging step by step', 'is_correct' => true],
                    ['text' => 'Langsung hapus semua code dan mulai dari awal', 'is_correct' => false],
                    ['text' => 'Abaikan error dan lanjut ke materi berikutnya', 'is_correct' => false],
                    ['text' => 'Menyalahkan tools atau framework yang digunakan', 'is_correct' => false],
                ],
            ],
        ];

        // Pertanyaan spesifik berdasarkan keyword dalam nama materi
        $specificQuestions = [];

        if (stripos($materialName, 'pengenalan') !== false || stripos($materialName, 'dasar') !== false) {
            $specificQuestions = [
                [
                    'question' => "Mengapa penting memahami konsep dasar sebelum advanced topics?",
                    'options' => [
                        ['text' => 'Fondasi yang kuat memudahkan pemahaman konsep advanced', 'is_correct' => true],
                        ['text' => 'Tidak penting, bisa langsung ke advanced', 'is_correct' => false],
                        ['text' => 'Dasar hanya untuk pemula saja', 'is_correct' => false],
                        ['text' => 'Hanya buang-buang waktu', 'is_correct' => false],
                    ],
                ],
            ];
        }

        if (stripos($materialName, 'api') !== false || stripos($materialName, 'backend') !== false) {
            $specificQuestions = array_merge($specificQuestions, [
                [
                    'question' => "Apa itu RESTful API?",
                    'options' => [
                        ['text' => 'Architectural style untuk web services yang menggunakan HTTP methods', 'is_correct' => true],
                        ['text' => 'Database management system', 'is_correct' => false],
                        ['text' => 'Programming language', 'is_correct' => false],
                        ['text' => 'Frontend framework', 'is_correct' => false],
                    ],
                ],
                [
                    'question' => "HTTP method mana yang digunakan untuk mengambil data?",
                    'options' => [
                        ['text' => 'GET', 'is_correct' => true],
                        ['text' => 'POST', 'is_correct' => false],
                        ['text' => 'DELETE', 'is_correct' => false],
                        ['text' => 'PUT', 'is_correct' => false],
                    ],
                ],
                [
                    'question' => "Status code HTTP 404 menandakan apa?",
                    'options' => [
                        ['text' => 'Resource tidak ditemukan', 'is_correct' => true],
                        ['text' => 'Server error', 'is_correct' => false],
                        ['text' => 'Unauthorized', 'is_correct' => false],
                        ['text' => 'Success', 'is_correct' => false],
                    ],
                ],
            ]);
        }

        if (stripos($materialName, 'database') !== false || stripos($materialName, 'sql') !== false) {
            $specificQuestions = array_merge($specificQuestions, [
                [
                    'question' => "Apa fungsi PRIMARY KEY dalam database?",
                    'options' => [
                        ['text' => 'Unique identifier untuk setiap row dalam table', 'is_correct' => true],
                        ['text' => 'Untuk menyimpan password', 'is_correct' => false],
                        ['text' => 'Untuk membuat backup', 'is_correct' => false],
                        ['text' => 'Untuk enkripsi data', 'is_correct' => false],
                    ],
                ],
                [
                    'question' => "Apa perbedaan antara INNER JOIN dan LEFT JOIN?",
                    'options' => [
                        ['text' => 'INNER JOIN hanya menampilkan data yang match, LEFT JOIN menampilkan semua dari left table', 'is_correct' => true],
                        ['text' => 'Tidak ada perbedaan', 'is_correct' => false],
                        ['text' => 'LEFT JOIN lebih cepat dari INNER JOIN', 'is_correct' => false],
                        ['text' => 'INNER JOIN untuk NoSQL, LEFT JOIN untuk SQL', 'is_correct' => false],
                    ],
                ],
            ]);
        }

        if (stripos($materialName, 'javascript') !== false || stripos($materialName, 'frontend') !== false) {
            $specificQuestions = array_merge($specificQuestions, [
                [
                    'question' => "Apa itu DOM (Document Object Model)?",
                    'options' => [
                        ['text' => 'Programming interface untuk HTML dan XML documents', 'is_correct' => true],
                        ['text' => 'Database system', 'is_correct' => false],
                        ['text' => 'CSS framework', 'is_correct' => false],
                        ['text' => 'Server-side language', 'is_correct' => false],
                    ],
                ],
                [
                    'question' => "Apa perbedaan let, const, dan var dalam JavaScript?",
                    'options' => [
                        ['text' => 'let dan const adalah block-scoped, var adalah function-scoped', 'is_correct' => true],
                        ['text' => 'Tidak ada perbedaan, hanya naming convention', 'is_correct' => false],
                        ['text' => 'const hanya untuk constants dalam math', 'is_correct' => false],
                        ['text' => 'var adalah yang terbaru dan paling direkomendasikan', 'is_correct' => false],
                    ],
                ],
            ]);
        }

        if (stripos($materialName, 'python') !== false || stripos($materialName, 'machine learning') !== false) {
            $specificQuestions = array_merge($specificQuestions, [
                [
                    'question' => "Apa kegunaan library NumPy dalam Python?",
                    'options' => [
                        ['text' => 'Untuk numerical computing dan array operations', 'is_correct' => true],
                        ['text' => 'Untuk web development', 'is_correct' => false],
                        ['text' => 'Untuk game development', 'is_correct' => false],
                        ['text' => 'Untuk mobile development', 'is_correct' => false],
                    ],
                ],
                [
                    'question' => "Apa itu supervised learning dalam Machine Learning?",
                    'options' => [
                        ['text' => 'Learning dari labeled data untuk membuat predictions', 'is_correct' => true],
                        ['text' => 'Learning tanpa data sama sekali', 'is_correct' => false],
                        ['text' => 'Learning yang selalu benar 100%', 'is_correct' => false],
                        ['text' => 'Learning yang memerlukan supervisor manusia setiap saat', 'is_correct' => false],
                    ],
                ],
            ]);
        }

        if (stripos($materialName, 'security') !== false || stripos($materialName, 'authentication') !== false) {
            $specificQuestions = array_merge($specificQuestions, [
                [
                    'question' => "Apa itu JWT (JSON Web Token)?",
                    'options' => [
                        ['text' => 'Token-based authentication method untuk web applications', 'is_correct' => true],
                        ['text' => 'JavaScript framework', 'is_correct' => false],
                        ['text' => 'Database type', 'is_correct' => false],
                        ['text' => 'CSS preprocessor', 'is_correct' => false],
                    ],
                ],
                [
                    'question' => "Mengapa penting melakukan input validation?",
                    'options' => [
                        ['text' => 'Untuk mencegah security vulnerabilities seperti SQL injection dan XSS', 'is_correct' => true],
                        ['text' => 'Hanya untuk user experience', 'is_correct' => false],
                        ['text' => 'Tidak penting, browser sudah handle otomatis', 'is_correct' => false],
                        ['text' => 'Hanya diperlukan untuk form registration', 'is_correct' => false],
                    ],
                ],
            ]);
        }

        if (stripos($materialName, 'testing') !== false || stripos($materialName, 'deployment') !== false) {
            $specificQuestions = array_merge($specificQuestions, [
                [
                    'question' => "Apa perbedaan unit testing dan integration testing?",
                    'options' => [
                        ['text' => 'Unit testing test individual components, integration testing test combined components', 'is_correct' => true],
                        ['text' => 'Tidak ada perbedaan signifikan', 'is_correct' => false],
                        ['text' => 'Integration testing hanya untuk frontend', 'is_correct' => false],
                        ['text' => 'Unit testing hanya untuk backend', 'is_correct' => false],
                    ],
                ],
                [
                    'question' => "Apa itu CI/CD?",
                    'options' => [
                        ['text' => 'Continuous Integration / Continuous Deployment', 'is_correct' => true],
                        ['text' => 'Computer Interface / Computer Development', 'is_correct' => false],
                        ['text' => 'Code Integration / Code Deployment', 'is_correct' => false],
                        ['text' => 'Cloud Infrastructure / Cloud Database', 'is_correct' => false],
                    ],
                ],
            ]);
        }

        // Tambahkan pertanyaan tentang best practices
        $practiceQuestions = [
            [
                'question' => "Apa yang dimaksud dengan 'clean code'?",
                'options' => [
                    ['text' => 'Code yang mudah dibaca, dipahami, dan dimaintain', 'is_correct' => true],
                    ['text' => 'Code yang tidak ada error', 'is_correct' => false],
                    ['text' => 'Code yang paling pendek', 'is_correct' => false],
                    ['text' => 'Code yang paling cepat', 'is_correct' => false],
                ],
            ],
            [
                'question' => "Kapan sebaiknya melakukan refactoring?",
                'options' => [
                    ['text' => 'Ketika code bekerja tapi sulit dipahami atau dimaintain', 'is_correct' => true],
                    ['text' => 'Hanya ketika ada bug', 'is_correct' => false],
                    ['text' => 'Tidak pernah, jika sudah berjalan', 'is_correct' => false],
                    ['text' => 'Setiap hari tanpa alasan jelas', 'is_correct' => false],
                ],
            ],
        ];

        // Gabungkan semua pertanyaan
        return array_merge($generalQuestions, $specificQuestions, $practiceQuestions);
    }
}

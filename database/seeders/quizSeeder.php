<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\quiz;
use App\Models\quiz_attempt;
use App\Models\quiz_question;
use App\Models\quiz_option;
use App\Models\material;

class quizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $materialIds = [1, 2, 3]; // daftar material_id yang ingin dibuatkan quiz

        foreach ($materialIds as $materialId) {
            // Buat quiz untuk tiap material
            $quiz = Quiz::create([
                'material_id' => $materialId,
                'judul_quiz' => "Materi Laravel $materialId",
                'is_required' => true,
            ]);

            // Buat 10 pertanyaan untuk tiap quiz
            for ($i = 1; $i <= 10; $i++) {
                $question = quiz_question::create([
                    'quiz_id' => $quiz->id,
                    'pertanyaan' => "Pertanyaan ke-$i untuk material $materialId: Apa itu Laravel?",
                ]);

                // Pilihan jawaban
                $options = [
                    'Framework PHP',
                    'Bahasa pemrograman',
                    'Database',
                    'Editor teks',
                ];

                // Pilih jawaban benar secara acak
                $correctIndex = array_rand($options);

                foreach ($options as $index => $optionText) {
                    quiz_option::create([
                        'quiz_question_id' => $question->id,
                        'teks_pilihan' => $optionText,
                        'is_correct' => $index === $correctIndex,
                    ]);
                }
            }
        }
    }
}

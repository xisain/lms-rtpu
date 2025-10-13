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

        $quiz = Quiz::create([
            'material_id' => material::get()->first()->id,
            'judul_quiz' => 'Materi Laravel 1',
            'is_required' => true,
        ]);

        // Buat 10 pertanyaan
        for ($i = 1; $i <= 10; $i++) {
            $question = quiz_question::create([
                'quiz_id' => $quiz->id,
                'pertanyaan' => "Pertanyaan ke-$i: Apa itu Laravel?",
            ]);

            // Buat 4 pilihan jawaban
            $options = [
                'Framework PHP',
                'Bahasa pemrograman',
                'Database',
                'Editor teks',
            ];

            // Tentukan jawaban benar secara acak
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

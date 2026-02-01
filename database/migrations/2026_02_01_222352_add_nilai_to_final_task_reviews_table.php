<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('final_task_reviews', function (Blueprint $table) {
            $table->decimal('nilai', 5, 2)->nullable()->comment('Nilai akhir untuk final task');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('final_task_reviews', function (Blueprint $table) {
            $table->dropColumn('nilai');
        });
    }
};

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
        Schema::create('courses', function (Blueprint $table) {
            $table->id()->index();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('nama_course')->unique();
            $table->string('image_link');
            $table->string('slugs');
            $table->text('description');
            $table->boolean('isLimitedCourse')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('maxEnrollment')->nullable();
            $table->boolean('public')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

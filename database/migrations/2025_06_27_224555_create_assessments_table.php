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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->nullable()->constrained()->onDelete('set null'); // Link to materials, nullable
            $table->string('title');
            $table->enum('type', ['quiz', 'exam', 'assignment', 'activity', 'other']);
            $table->text('description')->nullable();
            $table->string('assessment_file_path')->nullable(); // For uploaded files
            $table->integer('duration_minutes')->nullable(); // For quizzes/exams
            $table->string('access_code')->nullable(); // For quizzes/exams
            $table->timestamp('available_at')->nullable();
            $table->timestamp('unavailable_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Who created it
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};


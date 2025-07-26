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
            Schema::create('submitted_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('submitted_assessment_id')->constrained('submitted_assessments')->onDelete('cascade');
                $table->foreignId('question_id')->constrained('questions')->onDelete('cascade'); // Link to original question
                $table->text('question_text'); // Snapshot of the question text
                $table->string('question_type'); // e.g., 'multiple_choice', 'true_false', 'essay'
                $table->integer('max_points');
                $table->text('submitted_answer')->nullable(); // For essay or direct text input
                $table->boolean('is_correct')->nullable(); // Nullable until graded
                $table->integer('score_earned')->nullable(); // Nullable until graded
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submitted_questions');
    }
};
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
            $table->foreignId('submitted_assessment_id')->constrained('submitted_assessments')->onDelete('cascade'); // Link to the overall submission
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade'); // Link to the original question
            $table->text('submitted_answer')->nullable(); // Stores the student's answer (text for essay/identification, index for MC, 'true'/'false' for TF)
            $table->boolean('is_correct')->nullable(); // For auto-gradable questions
            $table->integer('score_earned')->nullable(); // Score for this specific question
            $table->timestamps();
            $table->unique(['submitted_assessment_id', 'question_id']);
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
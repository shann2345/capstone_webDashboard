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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->text('question_text'); // The actual question content
            $table->enum('question_type', ['multiple_choice', 'identification', 'true_false']); // Type of question
            $table->integer('points')->default(1); // Points for the question
            $table->text('correct_answer')->nullable(); // Stores the correct answer (index for MC, text for ID, 'true'/'false' for TF)
            $table->integer('order')->nullable(); // Display order within the assessment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};


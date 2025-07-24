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
        Schema::create('submitted_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submitted_question_id')->constrained('submitted_questions')->onDelete('cascade'); // Link to the submitted question
            $table->foreignId('question_option_id')->constrained('question_options')->onDelete('cascade'); // Link to the original option chosen
            $table->timestamps();
            // Optional: Ensure a specific option is only submitted once per submitted question
            $table->unique(['submitted_question_id', 'question_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submitted_question_options');
    }
};
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
                $table->foreignId('submitted_question_id')->constrained('submitted_questions')->onDelete('cascade');
                $table->foreignId('question_option_id')->constrained('question_options')->onDelete('cascade'); // Link to original option
                $table->text('option_text'); // Snapshot of the option text
                $table->boolean('is_correct_option'); // Was this option correct at the time of snapshot?
                $table->boolean('is_selected')->default(false); // Did the student select this option?
                $table->timestamps();
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
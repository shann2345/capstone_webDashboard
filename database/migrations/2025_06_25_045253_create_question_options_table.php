<?php

// php artisan make:migration create_question_options_table --create=question_options
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false); // True for the correct option
            $table->integer('order')->nullable(); // For sequencing options
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
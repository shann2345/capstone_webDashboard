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
        Schema::create('assessments', function (Blueprint $table) { // Conventionally, table name should be plural 'assessments'
            $table->id();
            $table->foreignId('course_id')
                  ->constrained('courses') // <-- FIX: Changed to 'courses' (plural)
                  ->onDelete('cascade');
            
            $table->foreignId('material_id')
                  ->nullable()
                  ->constrained('materials')
                  ->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable(); // Changed to text as descriptions can be long

            $table->string('encrypted_file_path')->nullable(); // <-- ADD: Made nullable for flexibility
            $table->string('original_filename')->nullable();   // <-- ADD: Made nullable for flexibility

            // <-- IMPROVEMENT: Using enum for predefined types
            $table->enum('type', ['quiz', 'activity', 'exam', 'assignment', 'project', 'other'])
                  ->default('quiz'); // Set a default type

            // Availability control - Made nullable for flexibility
            $table->timestamp('available_at')->nullable();   // <-- ADD: Made nullable
            $table->timestamp('unavailable_at')->nullable(); // <-- ADD: Made nullable

            $table->integer('duration_minutes')->nullable();

            $table->string('access_code')->nullable()->unique(); // If you want an access code for assessments too

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
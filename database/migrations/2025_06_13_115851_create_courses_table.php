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
            $table->id(); // Auto-incrementing primary key (course ID)

            // Basic Course Information
            $table->string('title');         // e.g., "Introduction to Programming"
            $table->string('course_code')->unique(); // e.g., "CS101", useful for identifying courses uniquely
            $table->text('description')->nullable(); // Longer description of the course
            $table->integer('credits')->nullable(); // e.g., 3 credits, 6 units (optional)

            // Foreign Key to Department
            // A course must belong to a department
            $table->foreignId('department_id')
                  ->constrained('departments') // Links to the 'id' column in the 'departments' table
                  ->onDelete('restrict');     // Prevents deletion of a department if courses are linked to it

            // Foreign Key to Instructor (User)
            // An instructor is a 'user' with the 'instructor' role
            $table->foreignId('instructor_id') // Laravel automatically assumes 'users' table and 'id' column for 'foreignId'
                  ->constrained('users')       // Links to the 'id' column in the 'users' table
                  ->onDelete('restrict');     // Prevents deletion of an instructor if they teach courses

            // Course Status (optional, but useful)
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // For course lifecycle

            $table->timestamps(); // `created_at` and `updated_at` timestamps
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

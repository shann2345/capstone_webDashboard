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
            $table->id();             // Auto-incrementing primary key (course ID)

            // Basic Course Information
            $table->string('title');         // e.g., "Introduction to Programming"
            $table->string('course_code')->unique(); // e.g., "CS101", must be unique
            $table->text('description')->nullable(); // Longer description, optional
            $table->integer('credits')->nullable(); // e.g., 3 credits/units, optional

            // Foreign Key to `departments` table
            // A course must belong to a department
            $table->foreignId('department_id')
                  ->constrained('departments') // Links to 'id' in 'departments' table
                  ->onDelete('restrict');     // Prevents deleting a department if courses are linked to it

            // Foreign Key to `users` table for the instructor
            // An instructor is a 'user' with the 'instructor' role
            $table->foreignId('instructor_id') // Laravel automatically assumes 'users' table and 'id' column for 'foreignId'
                  ->constrained('users')       // Links to 'id' in 'users' table
                  ->onDelete('restrict');     // Prevents deleting an instructor if they teach courses

            // Course Status (optional, but useful for course lifecycle)
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->timestamps();     // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses'); // If you rollback this migration, the table is dropped.
    }
};
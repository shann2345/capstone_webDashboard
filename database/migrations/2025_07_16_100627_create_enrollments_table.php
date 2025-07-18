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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id(); // Primary key for the enrollment record itself

            // Foreign key for student (user ID)
            $table->foreignId('student_id')
                  ->constrained('users') // Assuming students are also in the 'users' table
                  ->onDelete('cascade'); // If a user is deleted, their enrollments are deleted

            // Foreign key for course ID
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->onDelete('cascade'); // If a course is deleted, its enrollments are deleted

            // Optional: Add unique constraint to prevent duplicate enrollments
            $table->unique(['student_id', 'course_id']);

            // Optional: Add enrollment-specific fields
            $table->enum('status', ['enrolled', 'completed', 'dropped'])->default('enrolled');
            $table->date('enrollment_date')->nullable();
            $table->decimal('grade', 5, 2)->nullable(); // Example: for grades if applicable

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
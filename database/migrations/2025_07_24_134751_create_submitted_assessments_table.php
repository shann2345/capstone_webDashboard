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
       Schema::create('submitted_assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
                $table->integer('score')->nullable(); // Nullable for assignments or incomplete quizzes
                $table->string('status')->default('in_progress'); // e.g., 'in_progress', 'completed', 'graded'
                $table->timestamp('submitted_at')->nullable(); // When the student finalizes the submission
                $table->timestamp('started_at')->useCurrent(); // When the student started the attempt
                $table->timestamp('completed_at')->nullable(); // When the student finalized the attempt
                $table->string('submitted_file_path')->nullable(); // For assignment submissions
                $table->timestamps(); // created_at (for initial record), updated_at
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submitted_assessments');
    }
};

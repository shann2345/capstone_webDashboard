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
        Schema::table('submitted_assessments', function (Blueprint $table) {
            // Add instructor feedback column
            $table->text('instructor_feedback')->nullable()->after('original_filename');
            
            // Add graded_at timestamp column
            $table->timestamp('graded_at')->nullable()->after('instructor_feedback');
            
            // Change score column to decimal to support assignment scoring (e.g., 9.1 out of 10)
            $table->decimal('score', 4, 1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submitted_assessments', function (Blueprint $table) {
            // Remove instructor feedback column
            $table->dropColumn('instructor_feedback');
            
            // Remove graded_at column
            $table->dropColumn('graded_at');
            
            // Revert score column back to integer
            $table->integer('score')->nullable()->change();
        });
    }
};

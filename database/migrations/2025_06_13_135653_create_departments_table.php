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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();             // Auto-incrementing primary key (e.g., 1, 2, 3...)
            $table->string('name')->unique(); // Name of the department (e.g., 'Computer Science', 'Mathematics'). 'unique()' means no two departments can have the same name.
            $table->timestamps();     // Adds 'created_at' and 'updated_at' columns automatically
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments'); // If you rollback this migration, the table is dropped.
    }
};
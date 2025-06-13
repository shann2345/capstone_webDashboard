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
        Schema::table('users', function (Blueprint $table) {
            // Add a foreign key column named 'department_id'
            // ->nullable(): Means a user doesn't *have* to belong to a department (e.g., admin or students without an assigned dept).
            // ->constrained('departments'): Links this column to the 'id' column in the 'departments' table.
            // ->onDelete('set null'): If a department record is deleted, any users linked to it will have their 'department_id' set to NULL.
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // When rolling back, first drop the foreign key constraint
            $table->dropConstrainedForeignId('department_id');
            // Then drop the column itself
            $table->dropColumn('department_id');
        });
    }
};
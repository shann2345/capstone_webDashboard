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
            // Add a foreign key column for department_id
            // nullable() means a user doesn't *have* to belong to a department (e.g., admin or students without assigned dept)
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments') // Constrains to the 'id' column of the 'departments' table
                  ->onDelete('set null'); // If a department is deleted, set user's department_id to NULL
                                        // You could also use ->onDelete('cascade') if you want users to be deleted with department
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropConstrainedForeignId('department_id');
            // Then drop the column
            $table->dropColumn('department_id');
        });
    }
};

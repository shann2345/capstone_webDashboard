<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_materials_table.php

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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            // Link to the course it belongs to
            $table->foreignId('course_id')->constrained('courses') ->onDelete('cascade');  
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // Path to the uploaded file, nullable if it's just text/link
            $table->string('original_filename')->nullable(); // Store original filename for download

            $table->string('material_type')->default('document'); // e.g., 'pdf', 'video', 'link', 'text', 'document'

            // Timestamps for availability control
            $table->timestamp('available_at')->nullable();
            $table->timestamp('unavailable_at')->nullable();

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

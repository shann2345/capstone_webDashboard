<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('notification_hash'); // Hash of the notification content
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // A student should only have one notification record per unique activity.
            $table->unique(['student_id', 'notification_hash']);
            $table->index(['student_id', 'is_read']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_notifications');
    }
};
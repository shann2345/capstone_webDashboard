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
            $table->string('profile_image')->nullable()->after('email');
            $table->string('phone')->nullable()->after('profile_image');
            $table->text('bio')->nullable()->after('phone');
            $table->string('department')->nullable()->after('bio');
            $table->string('title')->nullable()->after('department'); // e.g., "Professor", "Associate Professor"
            $table->date('birth_date')->nullable()->after('title');
            $table->string('gender')->nullable()->after('birth_date');
            $table->text('address')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_image',
                'phone',
                'bio',
                'department',
                'title',
                'birth_date',
                'gender',
                'address'
            ]);
        });
    }
};

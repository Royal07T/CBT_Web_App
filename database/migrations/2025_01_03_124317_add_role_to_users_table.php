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
        // Add the 'role' column to the 'users' table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student'); // Default role is 'student'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the 'role' column if the migration is rolled back
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};

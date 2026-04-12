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
        Schema::table('peminjaman', function (Blueprint $table) {
            // Change the status column to add 'pending' status
            $table->string('status')->change(); // Convert to string first to avoid enum issues
        });

        // Now update to use varchar and update enum values via raw SQL
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('pending', 'dipinjam', 'dikembalikan', 'terlambat', 'ditolak') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'dipinjam'");
    }
};

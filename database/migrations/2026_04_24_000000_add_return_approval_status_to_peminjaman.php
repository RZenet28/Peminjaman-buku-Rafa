<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status enum to include 'menunggu_persetujuan_pengembalian'
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('pending', 'dipinjam', 'menunggu_persetujuan_pengembalian', 'dikembalikan', 'terlambat', 'ditolak') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the new status
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('pending', 'dipinjam', 'dikembalikan', 'terlambat', 'ditolak') DEFAULT 'pending'");
    }
};

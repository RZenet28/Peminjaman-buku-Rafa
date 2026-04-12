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
            // Drop the old foreign key constraint
            $table->dropForeign(['buku_id']);
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            // Add new foreign key pointing to books table
            $table->foreign('buku_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['buku_id']);
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            // Restore the old foreign key
            $table->foreign('buku_id')->references('id')->on('buku')->onDelete('cascade');
        });
    }
};

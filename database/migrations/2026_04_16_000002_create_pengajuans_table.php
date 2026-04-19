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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            // For tracking and indexing
            $table->index(['user_id', 'status']);
        });

        // Modify peminjaman table to add pengajuan_id
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->foreignId('pengajuan_id')->nullable()->constrained('pengajuans')->onDelete('cascade')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeignKey(['pengajuan_id']);
            $table->dropColumn('pengajuan_id');
        });

        Schema::dropIfExists('pengajuans');
    }
};

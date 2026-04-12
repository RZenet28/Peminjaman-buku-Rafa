<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all data from buku table
        $bukus = DB::table('buku')->get();

        foreach ($bukus as $buku) {
            // Find or create category based on kategori field
            $category = null;
            if ($buku->kategori) {
                $category = Category::firstOrCreate(
                    ['name' => $buku->kategori],
                    ['name' => $buku->kategori]
                );
            }

            // Check if book already exists in books table
            $existingBook = DB::table('books')->where('nama_buku', $buku->judul)->first();

            if (!$existingBook) {
                // Insert into books table
                DB::table('books')->insert([
                    'nama_buku' => $buku->judul,
                    'deskripsi' => $buku->deskripsi,
                    'stock' => $buku->stok ?? 0,
                    'gambar' => $buku->cover,
                    'category_id' => $category ? $category->id : null,
                    'denda_hilang' => 0,
                    'denda_rusak' => 0,
                    'created_at' => $buku->created_at ?? now(),
                    'updated_at' => $buku->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete synced books - optional, comment out if you want to keep them
        // DB::table('books')->truncate();
    }
};

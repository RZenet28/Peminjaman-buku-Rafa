<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'nama_buku',
        'pengarang',
        'penerbit',
        'tahun',
        'isbn',
        'deskripsi',
        'stock',
        'gambar',
        'category_id',
        'denda_hilang',
        'denda_rusak'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
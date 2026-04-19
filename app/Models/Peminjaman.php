<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;
    
    protected $table = 'peminjaman';
    
    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_pengembalian',
        'status',
        'denda',
        'catatan'
    ];
    
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_pengembalian' => 'date',
    ];
    
    /**
     * Relasi ke user (anggota)
     */
    public function anggota()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke user (alias untuk anggota)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke buku
     */
    public function buku()
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }

    /**
     * Relasi ke pengajuan (request group)
     */
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
    
    /**
     * Scope untuk peminjaman yang sedang dipinjam
     */
    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }
    
    /**
     * Scope untuk peminjaman yang terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'dipinjam')
                    ->where('tanggal_kembali', '<', Carbon::now());
    }
    
    /**
     * Check apakah peminjaman terlambat
     */
    public function isTerlambat()
    {
        if ($this->status !== 'dipinjam') {
            return false;
        }
        
        return Carbon::parse($this->tanggal_kembali)->isPast();
    }
    
    /**
     * Hitung jumlah hari terlambat
     */
    public function hitungHariTerlambat()
    {
        if (!$this->isTerlambat()) {
            return 0;
        }
        return Carbon::parse($this->tanggal_kembali)->diffInDays(Carbon::now());
    }
    
    /**
     * Hitung denda (misal 1000 per hari)
     */
    public function hitungDenda($dendaPerHari = 1000)
    {
        $hariTerlambat = $this->hitungHariTerlambat();
        return $hariTerlambat * $dendaPerHari;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;

class PetugasController extends Controller
{
    /**
     * Display petugas dashboard
     */
    public function dashboard()
    {
        // Total buku
        $totalBuku = Buku::count();
        
        // Pending applications
        $peminjamanPending = Peminjaman::where('status', 'pending')->count();
        
        // Buku yang sedang dipinjam
        $bukuDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        
        // Buku terlambat (melewati tanggal kembali)
        $bukuTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->count();
        
        // Total anggota (users dengan role anggota/siswa)
        $totalAnggota = User::where('role', 'anggota')
            ->orWhere('role', 'siswa')
            ->count();
        
        // Peminjaman terbaru (5 terakhir)
        $peminjamanTerbaru = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'dipinjam')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Buku populer (paling banyak dipinjam)
        $bukuPopuler = Buku::withCount(['peminjaman' => function($query) {
                $query->where('status', 'selesai');
            }])
            ->orderBy('peminjaman_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($buku) {
                $buku->total_dipinjam = $buku->peminjaman_count;
                return $buku;
            });
        
        return view('petugas.dashboard', compact(
            'totalBuku',
            'peminjamanPending',
            'bukuDipinjam',
            'bukuTerlambat',
            'totalAnggota',
            'peminjamanTerbaru',
            'bukuPopuler'
        ));
    }

    /**
     * Display pending loan applications for approval
     */
    public function persetujuanPeminjaman()
    {
        $loans = Peminjaman::with(['user', 'buku'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('petugas.persetujuan_peminjaman', compact('loans'));
    }

    /**
     * Approve a loan application
     */
    public function approvePeminjaman($id)
    {
        $loan = Peminjaman::findOrFail($id);
        
        // Get the book
        $book = $loan->buku;
        
        // Check if stock is available
        if (!$book || $book->stock < 1) {
            return redirect()->route('petugas.persetujuan')->with('error', 'Stok buku tidak tersedia!');
        }
        
        // Reduce stock
        $book->decrement('stock');
        
        // Update status to approved
        $loan->update(['status' => 'dipinjam']);

        return redirect()->route('petugas.persetujuan')->with('success', 'Peminjaman telah disetujui! Stok berkurang 1.');
    }

    /**
     * Reject a loan application
     */
    public function rejectPeminjaman($id)
    {
        $loan = Peminjaman::findOrFail($id);
        
        // Update status to rejected
        $loan->update(['status' => 'ditolak']);

        return redirect()->route('petugas.persetujuan')->with('success', 'Peminjaman telah ditolak!');
    }
}
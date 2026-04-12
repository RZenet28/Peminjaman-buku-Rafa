<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PeminjamController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user-specific statistics
        $bukuDipinjam = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();
            
        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();
        
        $bukuTerlambat = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->count();
        
        // Get current active loans with book details
        $peminjamanAktif = Peminjaman::with(['buku'])
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_kembali', 'asc')
            ->get();
        
        // Get popular books across system
        $bukuPopuler = Book::latest()->limit(5)->get();

        return view('peminjam.dashboard', compact(
            'bukuDipinjam',
            'totalPeminjaman',
            'bukuTerlambat',
            'peminjamanAktif',
            'bukuPopuler'
        ));
    }
    
    /**
     * Display user profile with loan history
     */
    public function profile()
    {
        $user = Auth::user();
        
        // Get profile statistics
        $stats = [
            'total' => Peminjaman::where('user_id', $user->id)->count(),
            'active' => Peminjaman::where('user_id', $user->id)->where('status', 'dipinjam')->count(),
            'completed' => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
            'rejected' => Peminjaman::where('user_id', $user->id)->where('status', 'ditolak')->count(),
            'late' => Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->where('tanggal_kembali', '<', Carbon::now())
                ->count(),
        ];
        
        // Get loan history with pagination
        $history = Peminjaman::with(['buku'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        
        // Calculate total fines
        $totalFines = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dikembalikan')
            ->sum('denda');
        
        return view('peminjam.profile.history', compact('user', 'stats', 'history', 'totalFines'));
    }

    public function daftarBuku()
    {
        // Ambil data buku
        $books = Book::all(); 

        return view('peminjam.daftar_buku', compact('books'));
    }

    public function ajukanPeminjaman()
    {
        // Mengambil semua buku agar user bisa memilih buku mana yang mau dipinjam
        $books = Book::all(); 

        return view('peminjam.ajukan_peminjaman', compact('books'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'buku_id' => 'required|exists:books,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
        ]);

        // Buat record peminjaman baru
        $tanggalPinjam = Carbon::createFromFormat('Y-m-d', $validated['tanggal_pinjam']);
        $tanggalKembali = $tanggalPinjam->copy()->addDays(7); // Standar 7 hari

        Peminjaman::create([
            'user_id' => auth()->user()->id,
            'buku_id' => $validated['buku_id'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'pending', // Status awal: menunggu persetujuan petugas
        ]);

        return redirect()->route('peminjam.dashboard')->with('success', 'Pengajuan peminjaman berhasil dikirim!');
    }

    /**
     * Display active loans to return
     */
    public function kembalikanBuku()
    {
        $loans = Peminjaman::with('buku')
            ->where('user_id', auth()->user()->id)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        return view('peminjam.kembali_buku', compact('loans'));
    }

    /**
     * Process book return
     */
    public function prosesKembaliaBuku(Request $request, $id)
    {
        $loan = Peminjaman::findOrFail($id);

        // Verify the loan belongs to the current user
        if ($loan->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'tanggal_pengembalian' => 'required|date',
            'catatan' => 'nullable|string|max:500',
        ]);

        $returnDate = Carbon::createFromFormat('Y-m-d', $validated['tanggal_pengembalian']);
        $dueDate = Carbon::parse($loan->tanggal_kembali);

        // Calculate fine if late
        $denda = 0;
        if ($returnDate->isAfter($dueDate)) {
            $daysLate = $returnDate->diffInDays($dueDate);
            $denda = $daysLate * 5000; // 5000 per day
        }

        // Restore stock when book is returned
        $book = $loan->buku;
        if ($book) {
            $book->increment('stock');
        }

        // Update loan record
        $loan->update([
            'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
            'status' => 'dikembalikan',
            'denda' => $denda,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('peminjam.dashboard')->with('success', 'Buku berhasil dikembalikan!' . ($denda > 0 ? " Denda: Rp " . number_format($denda) : ''));
    }
}
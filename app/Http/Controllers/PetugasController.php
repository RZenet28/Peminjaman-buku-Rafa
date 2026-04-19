<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\Pengajuan;
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
        $totalBuku = Book::count();
        
        // Total stok buku
        $totalStok = Book::sum('stock');
        
        // Pending applications
        $peminjamanPending = Peminjaman::where('status', 'pending')->count();
        
        // Buku yang sedang dipinjam
        $bukuDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        
        // Buku terlambat (melewati tanggal kembali)
        $bukuTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->count();
        
        // Total anggota/peminjam (users dengan role peminjam, siswa, atau anggota)
        $totalAnggota = User::whereIn('role', ['peminjam', 'siswa', 'anggota'])
            ->count();
        
        // Peminjaman terbaru (5 terakhir)
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Peminjaman pending untuk dashboard
        $peminjamanPendingList = Peminjaman::with(['user', 'buku'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Buku populer (paling banyak dipinjam)
        $bukuPopuler = Book::withCount(['peminjaman' => function($query) {
                $query->whereIn('status', ['dipinjam', 'dikembalikan']);
            }])
            ->orderBy('peminjaman_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('petugas.dashboard', compact(
            'totalBuku',
            'totalStok',
            'peminjamanPending',
            'peminjamanPendingList',
            'bukuDipinjam',
            'bukuTerlambat',
            'totalAnggota',
            'peminjamanTerbaru',
            'bukuPopuler'
        ));
    }

    /**
     * Display pending loan applications for approval (grouped by request)
     */
    public function persetujuanPeminjaman()
    {
        $requests = Pengajuan::with(['user', 'peminjamans.buku'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas.persetujuan_peminjaman', compact('requests'));
    }

    /**
     * Approve a grouped loan request
     */
    public function approvePeminjaman($id)
    {
        $pengajuan = Pengajuan::with('peminjamans.buku')->findOrFail($id);
        
        // Check stock availability for all books in this request
        foreach ($pengajuan->peminjamans as $peminjaman) {
            if (!$peminjaman->buku || $peminjaman->buku->stock < 1) {
                return redirect()->route('petugas.persetujuan')
                    ->with('error', 'Stok untuk buku "' . ($peminjaman->buku->nama_buku ?? 'N/A') . '" tidak tersedia!');
            }
        }
        
        // Approve all peminjaman and decrement stock
        foreach ($pengajuan->peminjamans as $peminjaman) {
            // Reduce stock
            $peminjaman->buku->decrement('stock');
            
            // Update status to approved
            $peminjaman->update(['status' => 'dipinjam']);
        }
        
        // Update pengajuan status to approved
        $pengajuan->update(['status' => 'disetujui']);

        return redirect()->route('petugas.persetujuan')
            ->with('success', 'Permohonan peminjaman berhasil disetujui! ' . $pengajuan->peminjamans->count() . ' buku siap dipinjam.');
    }

    /**
     * Reject a grouped loan request
     */
    public function rejectPeminjaman($id)
    {
        $pengajuan = Pengajuan::with('peminjamans')->findOrFail($id);
        
        // Reject all peminjaman
        foreach ($pengajuan->peminjamans as $peminjaman) {
            $peminjaman->update(['status' => 'ditolak']);
        }
        
        // Update pengajuan status to rejected
        $pengajuan->update(['status' => 'ditolak']);

        return redirect()->route('petugas.persetujuan')
            ->with('success', 'Permohonan peminjaman telah ditolak!');
    }

    /**
     * Monitor book returns
     */
    public function monitorReturns(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku'])
            ->where('status', 'dipinjam');

        // Filter options
        if ($request->filter === 'overdue') {
            $query->where('tanggal_kembali', '<', Carbon::now());
        } elseif ($request->filter === 'today') {
            $query->whereDate('tanggal_kembali', Carbon::today());
        } elseif ($request->filter === 'soon') {
            $query->whereBetween('tanggal_kembali', [Carbon::now(), Carbon::now()->addDays(3)]);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('buku', function ($b) use ($request) {
                    $b->where('judul', 'like', '%' . $request->search . '%');
                });
            });
        }

        $returns = $query->orderBy('tanggal_kembali', 'asc')->paginate(15);

        return view('petugas.monitor_returns', compact('returns'));
    }

    /**
     * Record a book return
     */
    public function recordReturn(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'condition' => 'required|in:baik,rusak',
            'notes' => 'nullable|string',
        ]);

        // Increment book stock
        $peminjaman->buku->increment('stock');

        // Update peminjaman record
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_pengembalian' => Carbon::now(),
            'catatan' => $request->notes,
        ]);

        return redirect()->route('petugas.monitor_returns')
            ->with('success', 'Pengembalian buku berhasil dicatat');
    }

    /**
     * Display books for data recording
     */
    public function books(Request $request)
    {
        $query = Book::query();
        
        // Update field names for Book model
        if (isset($request->search)) {
            $request->merge([
                'search_term' => $request->search
            ]);
        }

        if ($request->search) {
            $query->where('nama_buku', 'like', '%' . $request->search . '%')
                ->orWhere('isbn', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori) {
            $query->where('category_id', $request->kategori);
        }

        $books = $query->paginate(15);
        // Get categories
        $categories = \App\Models\Category::all();

        return view('petugas.books.index', compact('books', 'categories'));
    }

    /**
     * Show book detail
     */
    public function bookDetail($id)
    {
        $book = Book::findOrFail($id);
        $borrowingHistory = Peminjaman::where('buku_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas.books.detail', compact('book', 'borrowingHistory'));
    }

    /**
     * Display borrowers for data recording
     */
    public function borrowers(Request $request)
    {
        $query = User::whereIn('role', ['peminjam', 'siswa']);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $borrowers = $query->paginate(15);

        return view('petugas.borrowers.index', compact('borrowers'));
    }

    /**
     * Show borrower detail and history
     */
    public function borrowerDetail($id)
    {
        $borrower = User::findOrFail($id);
        $borrowingHistory = Peminjaman::where('user_id', $id)
            ->with('buku')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $activeLoans = Peminjaman::where('user_id', $id)
            ->where('status', 'dipinjam')
            ->count();

        $totalLoans = Peminjaman::where('user_id', $id)->count();

        return view('petugas.borrowers.detail', compact(
            'borrower',
            'borrowingHistory',
            'activeLoans',
            'totalLoans'
        ));
    }

    /**
     * Display reporting page
     */
    public function reporting(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date) : Carbon::now()->endOfMonth();

        // Statistics
        $totalBorrowings = Peminjaman::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedBorrowings = Peminjaman::where('status', 'dikembalikan')
            ->whereBetween('tanggal_pengembalian', [$startDate, $endDate])
            ->count();
        $lateBorrowings = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->count();

        // Top books
        $topBooks = Book::withCount(['peminjaman' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderBy('peminjaman_count', 'desc')
            ->limit(10)
            ->get();

        // Top borrowers
        $topBorrowers = User::withCount(['peminjaman' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->whereIn('role', ['peminjam', 'siswa'])
            ->orderBy('peminjaman_count', 'desc')
            ->limit(10)
            ->get();

        return view('petugas.reporting', compact(
            'totalBorrowings',
            'completedBorrowings',
            'lateBorrowings',
            'topBooks',
            'topBorrowers',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export report
     */
    public function exportReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date) : Carbon::now()->endOfMonth();

        $borrowings = Peminjaman::with(['user', 'buku'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        if ($request->format === 'csv') {
            return $this->exportCSV($borrowings, $startDate, $endDate);
        } else {
            return $this->exportPDF($borrowings, $startDate, $endDate);
        }
    }

    /**
     * Export to CSV
     */
    private function exportCSV($borrowings, $startDate, $endDate)
    {
        $filename = 'laporan_peminjaman_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';

        $handle = fopen('php://memory', 'w');
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

        // Headers
        fputcsv($handle, ['Nama Peminjam', 'Email', 'Nama Buku', 'Tanggal Peminjaman', 'Tanggal Kembali', 'Status', 'Tanggal Pengembalian']);

        foreach ($borrowings as $borrow) {
            fputcsv($handle, [
                $borrow->user->name,
                $borrow->user->email,
                $borrow->buku->nama_buku ?? 'N/A',
                $borrow->created_at->format('Y-m-d H:i:s'),
                $borrow->tanggal_kembali->format('Y-m-d'),
                $borrow->status,
                $borrow->tanggal_pengembalian ? $borrow->tanggal_pengembalian->format('Y-m-d H:i:s') : '-',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export to PDF (simple HTML table)
     */
    private function exportPDF($borrowings, $startDate, $endDate)
    {
        $filename = 'laporan_peminjaman_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.html';

        $html = '<h2>Laporan Peminjaman Buku</h2>';
        $html .= '<p>Periode: ' . $startDate->format('d-m-Y') . ' hingga ' . $endDate->format('d-m-Y') . '</p>';
        $html .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width: 100%;">';
        $html .= '<tr><th>Nama Peminjam</th><th>Email</th><th>Nama Buku</th><th>Tanggal Peminjaman</th><th>Tanggal Kembali</th><th>Status</th><th>Tanggal Pengembalian</th></tr>';

        foreach ($borrowings as $borrow) {
            $html .= '<tr>';
            $html .= '<td>' . $borrow->user->name . '</td>';
            $html .= '<td>' . $borrow->user->email . '</td>';
            $html .= '<td>' . ($borrow->buku->nama_buku ?? 'N/A') . '</td>';
            $html .= '<td>' . $borrow->created_at->format('d-m-Y H:i') . '</td>';
            $html .= '<td>' . $borrow->tanggal_kembali->format('d-m-Y') . '</td>';
            $html .= '<td>' . $borrow->status . '</td>';
            $html .= '<td>' . ($borrow->tanggal_pengembalian ? $borrow->tanggal_pengembalian->format('d-m-Y H:i') : '-') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Display profile
     */
    public function profile()
    {
        $user = auth()->user();
        return view('petugas.profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('petugas.profile')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Show change password form
     */
    public function showChangePassword()
    {
        return view('petugas.change_password');
    }

    /**
     * Update password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('petugas.profile')
            ->with('success', 'Password berhasil diubah');
    }
}
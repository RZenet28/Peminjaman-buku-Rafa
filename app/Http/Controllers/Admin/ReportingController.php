<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportingController extends Controller
{
    /**
     * Display reporting dashboard
     */
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date) : Carbon::now()->endOfMonth();

        // Overall Statistics
        $totalBorrowings = Peminjaman::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedBorrowings = Peminjaman::where('status', 'dikembalikan')
            ->whereBetween('tanggal_pengembalian', [$startDate, $endDate])
            ->count();
        $rejectedBorrowings = Peminjaman::where('status', 'ditolak')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $lateBorrowings = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->count();

        // Top Books
        $topBooks = Book::withCount(['peminjaman' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderBy('peminjaman_count', 'desc')
            ->limit(10)
            ->get();

        // Top Users
        $topUsers = User::withCount(['peminjaman' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->where('role', 'peminjam')
            ->orWhere('role', 'siswa')
            ->orderBy('peminjaman_count', 'desc')
            ->limit(10)
            ->get();

        // Monthly Borrowing Trend
        $monthlyTrend = Peminjaman::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Fines Data
        $totalFines = Peminjaman::where('status', 'dikembalikan')
            ->whereBetween('tanggal_pengembalian', [$startDate, $endDate])
            ->sum('denda');

        return view('admin.reporting.index', compact(
            'totalBorrowings',
            'completedBorrowings',
            'rejectedBorrowings',
            'lateBorrowings',
            'topBooks',
            'topUsers',
            'monthlyTrend',
            'totalFines',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export report to CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date) : Carbon::now()->endOfMonth();

        $borrowings = Peminjaman::with(['user', 'buku'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $filename = 'laporan_peminjaman_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename"
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', 'Nama Peminjam', 'Nama Buku', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status', 'Denda']);

        foreach ($borrowings as $borrowing) {
            fputcsv($handle, [
                $borrowing->id,
                $borrowing->user->name ?? '-',
                $borrowing->buku->nama_buku ?? '-',
                $borrowing->tanggal_pinjam,
                $borrowing->tanggal_kembali,
                $borrowing->status,
                $borrowing->denda ?? 0
            ]);
        }

        fclose($handle);

        return response()->stream(function() {}, 200, $headers);
    }
}

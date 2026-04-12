<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with synchronized data
     */
    public function index(): View
    {
        // ========== STATISTICS ==========
        
        // Total books in system
        $totalBooks = Book::count();
        
        // Active borrowings (status: dipinjam)
        $borrowingsActive = Peminjaman::where('status', 'dipinjam')->count();
        
        // Late borrowings (status: dipinjam AND tanggal_kembali has passed)
        $borrowingsLate = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->count();
        
        // Total users/members
        $totalUsers = User::where('role', '!=', 'admin')->count();
        
        // Low stock books (stock < 5)
        $lowStockBooks = Book::where('stock', '<', 5)
            ->orderBy('stock')
            ->limit(5)
            ->get();
        
        // ========== AGGREGATIONS ==========
        
        // Top borrowed books
        $topBooks = Book::withCount(['peminjaman' => function($query) {
            $query->where('status', 'dipinjam');
        }])
        ->orderBy('peminjaman_count', 'desc')
        ->limit(4)
        ->get();
        
        // Top borrowers
        $topBorrowers = User::withCount(['peminjaman' => function($query) {
            $query->where('status', 'dipinjam');
        }])
        ->where('role', '!=', 'admin')
        ->orderBy('peminjaman_count', 'desc')
        ->limit(5)
        ->get();
        
        // Borrowing status distribution
        $borrowingStatus = [
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'ditolak' => Peminjaman::where('status', 'ditolak')->count(),
        ];
        
        // Recent borrowing activities
        $recentBorrowings = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Monthly borrowing trend (last 6 months)
        $monthlyBorrowings = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Peminjaman::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyBorrowings[] = [
                'month' => $month->format('M'),
                'count' => $count,
            ];
        }
        
        // Total fines
        $totalFines = Peminjaman::where('status', 'dikembalikan')
            ->sum('denda');
        
        // Return data to view
        return view('admin.dashboard', [
            'totalBooks' => $totalBooks,
            'borrowingsActive' => $borrowingsActive,
            'borrowingsLate' => $borrowingsLate,
            'totalUsers' => $totalUsers,
            'lowStockBooks' => $lowStockBooks,
            'topBooks' => $topBooks,
            'topBorrowers' => $topBorrowers,
            'borrowingStatus' => $borrowingStatus,
            'recentBorrowings' => $recentBorrowings,
            'monthlyBorrowings' => $monthlyBorrowings,
            'totalFines' => $totalFines,
        ]);
    }
}

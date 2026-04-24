<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HistoryController extends Controller
{
    /**
     * Display borrowing history
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        // Filter by status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by user or book
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('buku', function ($b) use ($request) {
                    $b->where('nama_buku', 'like', '%' . $request->search . '%');
                });
            });
        }

        // Filter by book
        if ($request->buku_id) {
            $query->where('buku_id', $request->buku_id);
        }

        $history = $query->latest()->paginate(20)->withQueryString();
        $books = Book::all();

        $stats = [
            'completed' => Peminjaman::where('status', 'dikembalikan')->count(),
            'on_time' => Peminjaman::where('status', 'dikembalikan')
                ->whereRaw('tanggal_pengembalian <= tanggal_kembali')
                ->count(),
            'late' => Peminjaman::where('status', 'dikembalikan')
                ->whereRaw('tanggal_pengembalian > tanggal_kembali')
                ->count(),
            'rejected' => Peminjaman::where('status', 'ditolak')->count(),
        ];

        return view('admin.history.index', compact('history', 'books', 'stats'));
    }

    /**
     * Show borrowing detail
     */
    public function show($id)
    {
        $borrowing = Peminjaman::with(['user', 'buku'])->findOrFail($id);
        $finePerDay = Cache::get('fine_per_day', 5000);
        return view('admin.history.show', compact('borrowing', 'finePerDay'));
    }

    /**
     * Update return date and recalculate fine
     */
    public function updateReturnDate(Request $request, $id)
    {
        $request->validate([
            'tanggal_pengembalian' => 'required|date'
        ]);

        $borrowing = Peminjaman::findOrFail($id);

        // Parse the new return date
        $newReturnDate = Carbon::createFromFormat('Y-m-d', $request->tanggal_pengembalian);
        $expectedReturnDate = Carbon::parse($borrowing->tanggal_kembali);
        
        // Calculate days late using timestamp
        // This is more reliable than diffInDays()
        $daysLate = 0;
        if ($newReturnDate > $expectedReturnDate) {
            // Calculate the difference in seconds, then convert to days
            $secondsDiff = $newReturnDate->timestamp - $expectedReturnDate->timestamp;
            $daysLate = (int) ceil($secondsDiff / (24 * 60 * 60));
        }
        
        // Get fine per day from settings (default: 5000)
        $finePerDay = Cache::get('fine_per_day', 5000);
        
        // Calculate new fine (always non-negative)
        $newFine = max(0, $daysLate * $finePerDay);
        
        // Update borrowing record
        $borrowing->update([
            'tanggal_pengembalian' => $newReturnDate,
            'denda' => $newFine
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tanggal pengembalian berhasil diperbarui',
            'data' => [
                'tanggal_pengembalian' => $newReturnDate->format('d M Y'),
                'daysLate' => $daysLate,
                'fine' => $newFine,
                'fineFormatted' => 'Rp ' . number_format($newFine)
            ]
        ]);
    }
}

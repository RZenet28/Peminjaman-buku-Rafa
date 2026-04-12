<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Book;
use Illuminate\Http\Request;

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
        return view('admin.history.show', compact('borrowing'));
    }
}

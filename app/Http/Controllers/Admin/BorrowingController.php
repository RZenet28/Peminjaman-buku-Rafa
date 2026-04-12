<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    /**
     * Display borrowing management page
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('buku', function ($b) use ($request) {
                    $b->where('nama_buku', 'like', '%' . $request->search . '%');
                });
            });
        }

        $borrowings = $query->latest()->paginate(15)->withQueryString();
        $books = Book::all();
        $users = User::where('role', 'peminjam')->orWhere('role', 'siswa')->get();

        $stats = [
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'ditolak' => Peminjaman::where('status', 'ditolak')->count(),
        ];

        return view('admin.borrowing.index', compact('borrowings', 'books', 'users', 'stats'));
    }

    /**
     * Approve borrowing request
     */
    public function approve($id)
    {
        $borrowing = Peminjaman::findOrFail($id);
        $book = $borrowing->buku;

        if (!$book || $book->stock < 1) {
            return back()->with('error', 'Stok buku tidak tersedia!');
        }

        $book->decrement('stock');
        $borrowing->update(['status' => 'dipinjam']);

        return back()->with('success', 'Peminjaman berhasil disetujui!');
    }

    /**
     * Reject borrowing request
     */
    public function reject($id)
    {
        $borrowing = Peminjaman::findOrFail($id);
        $borrowing->update(['status' => 'ditolak']);

        return back()->with('success', 'Peminjaman berhasil ditolak!');
    }

    /**
     * Manually create borrowing request (admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:books,id',
            'tanggal_pinjam' => 'required|date',
            'status' => 'required|in:pending,dipinjam,dikembalikan,ditolak',
        ]);

        Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => Carbon::createFromFormat('Y-m-d', $request->tanggal_pinjam)->addDays(7),
            'status' => $request->status,
        ]);

        if ($request->status === 'dipinjam') {
            $book = Book::find($request->buku_id);
            $book->decrement('stock');
        }

        return back()->with('success', 'Peminjaman berhasil dibuat!');
    }
}

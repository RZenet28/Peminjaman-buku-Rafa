<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get or create user's cart
     */
    private function getCart()
    {
        $user = Auth::user();
        return $user->cart ?? Cart::create(['user_id' => $user->id]);
    }

    /**
     * Display shopping cart
     */
    public function index()
    {
        $cart = $this->getCart();
        $cart->load('items.book');

        return view('peminjam.cart.index', compact('cart'));
    }

    /**
     * Add book to cart (AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $book = Book::findOrFail($request->book_id);
        $quantity = $request->quantity ?? 1;

        // Check if book is in stock
        if ($book->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup. Stok tersedia: ' . $book->stock,
            ], 422);
        }

        $cart = $this->getCart();

        // Check if already in cart
        if ($cart->hasBook($request->book_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Buku sudah ada di keranjang',
            ], 422);
        }

        $cart->addBook($request->book_id, $quantity);

        return response()->json([
            'success' => true,
            'message' => 'Buku ditambahkan ke keranjang',
            'cartCount' => $cart->items()->sum('quantity'),
        ]);
    }

    /**
     * Remove book from cart (AJAX)
     */
    public function remove(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $cart = $this->getCart();
        $cart->removeBook($request->book_id);

        return response()->json([
            'success' => true,
            'message' => 'Buku dihapus dari keranjang',
            'cartCount' => $cart->items()->count(),
        ]);
    }

    /**
     * Update quantity for book in cart (AJAX)
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Check if quantity doesn't exceed stock
        if ($request->quantity > $book->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok yang tersedia. Stok maksimal: ' . $book->stock,
            ], 422);
        }

        $cart = $this->getCart();
        $cartItem = $cart->items()->where('book_id', $request->book_id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan dalam keranjang',
            ], 404);
        }

        // Update quantity
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Jumlah buku berhasil diperbarui',
            'quantity' => $request->quantity,
            'totalQuantity' => $cart->items()->sum('quantity'),
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->clear();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang dikosongkan',
        ]);
    }

    /**
     * Get mini cart data (for sidebar/header)
     */
    public function getMini()
    {
        $cart = $this->getCart();
        $cart->load('items.book');

        return response()->json([
            'count' => $cart->items()->sum('quantity'),
            'items' => $cart->items()->with('book')->get(),
        ]);
    }

    /**
     * Checkout - Create peminjaman records for all cart items
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
        ]);

        $cart = $this->getCart();

        // Validate cart is not empty
        if ($cart->items()->count() === 0) {
            return redirect()->route('peminjam.cart.index')
                ->with('error', 'Keranjang Anda kosong');
        }

        $cart->load('items.book');
        $tanggalPinjam = Carbon::createFromFormat('Y-m-d', $validated['tanggal_pinjam']);
        $tanggalKembali = $tanggalPinjam->copy()->addDays(7); // Standard 7 days

        // Create one pengajuan (request) for this entire checkout
        $pengajuan = Pengajuan::create([
            'user_id' => Auth::user()->id,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'pending',
        ]);

        // Create peminjaman record for each item (considering quantity)
        $createdCount = 0;
        foreach ($cart->items as $item) {
            // Create one peminjaman per quantity requested
            for ($i = 0; $i < $item->quantity; $i++) {
                Peminjaman::create([
                    'pengajuan_id' => $pengajuan->id,
                    'user_id' => Auth::user()->id,
                    'buku_id' => $item->book_id,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => 'pending',
                ]);
                $createdCount++;
            }
        }

        // Clear cart after checkout
        $cart->clear();

        return redirect()->route('peminjam.dashboard')
            ->with('success', "Pengajuan peminjaman untuk {$createdCount} buku berhasil dikirim!");
    }
}

@extends('layouts.peminjam')

@section('content')
    <style>
        .cart-wrapper {
            padding: 40px;
            max-width: 1000px;
            margin: 0 auto;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .header-box {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-box h2 {
            font-size: 28px;
            color: #2d3436;
            font-weight: 700;
            margin: 0;
        }

        .badge-count {
            background-color: #6366f1;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 60px 40px;
            background: #f8fafc;
            border-radius: 20px;
            border: 2px dashed #cbd5e1;
        }

        .empty-cart-icon {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-cart p {
            color: #64748b;
            margin: 10px 0;
            font-size: 15px;
        }

        /* Cart Items */
        .cart-items-section {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid #edf2f7;
            margin-bottom: 30px;
        }

        .cart-items-section h3 {
            font-size: 18px;
            color: #2d3436;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #edf2f7;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 15px;
            border: 1px solid #edf2f7;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .item-info {
            flex: 1;
        }

        .item-title {
            font-size: 16px;
            color: #2d3436;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .item-meta {
            font-size: 13px;
            color: #718096;
        }

        .item-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 2px solid #edf2f7;
            border-radius: 8px;
            padding: 4px;
        }

        .qty-btn {
            background: #6366f1;
            color: white;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background: #4f46e5;
            transform: scale(1.05);
        }

        .qty-input {
            width: 50px;
            border: none;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            background: transparent;
            outline: none;
        }

        .btn-remove {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-remove:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Summary Section */
        .summary-section {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid #edf2f7;
            margin-bottom: 30px;
        }

        .summary-section h3 {
            font-size: 18px;
            color: #2d3436;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 15px;
            color: #4a5568;
            border-bottom: 1px solid #edf2f7;
        }

        .summary-item:last-child {
            border-bottom: none;
            padding-top: 20px;
            padding-bottom: 0;
            font-weight: 700;
            color: #2d3436;
            font-size: 16px;
        }

        .info-box {
            background: #f5f3ff;
            border-left: 4px solid #6366f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 0;
            font-size: 13px;
            color: #5b21b6;
            line-height: 1.5;
        }

        /* Date Input */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
        }

        .input-style {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 2px solid #edf2f7;
            background-color: #f8fafc;
            font-size: 15px;
            color: #2d3748;
            transition: all 0.3s ease;
            outline: none;
            box-sizing: border-box;
        }

        .input-style:focus {
            border-color: #6366f1;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* Action Buttons */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .btn-primary {
            flex: 1;
            background-color: #6366f1;
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            flex: 1;
            background-color: #e2e8f0;
            color: #2d3748;
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .btn-secondary:active {
            transform: translateY(0);
        }

        .btn-clear-cart {
            background-color: #f3f4f6;
            color: #ef4444;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-clear-cart:hover {
            background-color: #fecaca;
            color: #991b1b;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-error {
            background-color: #fecaca;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        @media (max-width: 768px) {
            .cart-wrapper {
                padding: 20px;
            }

            .header-box {
                flex-direction: column;
                align-items: flex-start;
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .item-actions {
                width: 100%;
                margin-top: 15px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>

    <div class="cart-wrapper">
        <div class="header-box">
            <div>
                <h2>Keranjang Peminjaman</h2>
                <p>Kelola buku yang ingin kamu pinjam</p>
            </div>
            <div class="badge-count">
                {{ $cart->items->sum('quantity') }} item
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($cart->items()->count() === 0)
            <div class="empty-cart">
                <div class="empty-cart-icon">📚</div>
                <p><strong>Keranjang Anda Kosong</strong></p>
                <p>Mulai tambahkan buku untuk peminjaman Anda</p>
                <a href="{{ route('peminjam.books.index') }}" class="btn-secondary"
                    style="display: inline-block; margin-top: 20px; padding: 12px 24px;">
                    Lihat Daftar Buku
                </a>
            </div>
        @else
            <div class="cart-items-section">
                <h3>Buku dalam Keranjang</h3>

                @foreach ($cart->items as $item)
                    <div class="cart-item">
                        <div class="item-info">
                            <div class="item-title">{{ $item->book->nama_buku }}</div>
                            <div class="item-meta">
                                Pengarang: <strong>{{ $item->book->pengarang }}</strong>
                                | Stok Tersedia: <strong>{{ $item->book->stock }}</strong>
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn qty-minus" data-book-id="{{ $item->book_id }}">−</button>
                                <input type="number" class="qty-input qty-display" value="{{ $item->quantity }}" min="1"
                                    max="{{ $item->book->stock }}" readonly>
                                <button type="button" class="qty-btn qty-plus" data-book-id="{{ $item->book_id }}"
                                    data-max="{{ $item->book->stock }}">+</button>
                            </div>
                            <form action="{{ route('peminjam.cart.remove') }}" method="POST" style="margin: 0;">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $item->book_id }}">
                                <button type="submit" class="btn-remove">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="summary-section">
                <h3>Ringkasan Peminjaman</h3>

                <div class="info-box">
                    <p>
                        <strong>ℹ Informasi:</strong> Durasi peminjaman standar adalah 7 hari.
                        Pastikan mengembalikan buku tepat waktu untuk menghindari denda keterlambatan.
                    </p>
                </div>

                <form action="{{ route('peminjam.cart.checkout') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="tanggal_pinjam">Tanggal Mulai Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="input-style"
                            min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="summary-item">
                        <span>Total Buku:</span>
                        <span id="totalBooksCount">{{ $cart->items->sum('quantity') }}</span>
                    </div>

                    <div class="summary-item">
                        <span>Tanggal Kembali (Perkiraan):</span>
                        <span id="estimatedReturn">{{ \Carbon\Carbon::now()->addDays(7)->format('d M Y') }}</span>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn-primary">
                            ✓ Lanjutkan ke Checkout
                        </button>
                        <a href="{{ route('peminjam.books.index') }}" class="btn-secondary">
                            ← Lanjut Belanja
                        </a>
                    </div>
                </form>

                <div style="text-align: center; margin-top: 20px;">
                    <form action="{{ route('peminjam.cart.clear') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-clear-cart"
                            onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
                            🗑 Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Update estimated return date
        document.getElementById('tanggal_pinjam').addEventListener('change', function () {
            const startDate = new Date(this.value);
            const returnDate = new Date(startDate);
            returnDate.setDate(returnDate.getDate() + 7);

            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            document.getElementById('estimatedReturn').textContent = returnDate.toLocaleDateString('id-ID', options);
        });

        // Quantity control handlers
        document.querySelectorAll('.qty-plus').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const bookId = this.dataset.bookId;
                const maxStock = parseInt(this.dataset.max);
                const qtyDisplay = this.closest('.quantity-controls').querySelector('.qty-display');
                let currentQty = parseInt(qtyDisplay.value);

                if (currentQty < maxStock) {
                    currentQty++;
                    updateQuantity(bookId, currentQty);
                } else {
                    alert('Stok tidak cukup! Maksimal: ' + maxStock);
                }
            });
        });

        document.querySelectorAll('.qty-minus').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const bookId = this.dataset.bookId;
                const qtyDisplay = this.closest('.quantity-controls').querySelector('.qty-display');
                let currentQty = parseInt(qtyDisplay.value);

                if (currentQty > 1) {
                    currentQty--;
                    updateQuantity(bookId, currentQty);
                } else {
                    alert('Minimal 1 buku harus ada dalam keranjang. Gunakan tombol Hapus untuk menghapus item.');
                }
            });
        });

        function updateQuantity(bookId, quantity) {
            fetch('{{ route("peminjam.cart.update-quantity") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    book_id: bookId,
                    quantity: quantity
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the quantity input display
                        const qtyInput = document.querySelector(`[data-book-id="${bookId}"].qty-display`);
                        if (qtyInput) {
                            qtyInput.value = quantity;
                        }

                        // Update the total books count
                        const totalBooksCount = document.getElementById('totalBooksCount');
                        if (totalBooksCount) {
                            totalBooksCount.textContent = data.totalQuantity;
                        }
                    } else {
                        alert(data.message || 'Gagal memperbarui keranjang');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui keranjang');
                });
        }
    </script>
@endsection
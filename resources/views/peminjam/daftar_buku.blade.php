@extends('layouts.peminjam')

@section('title', 'Daftar Koleksi Buku')

@section('content')
    <style>
        .book-card {
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
        }

        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .book-img-container {
            position: relative;
            overflow: hidden;
            aspect-ratio: 2/3;
        }

        .book-img-container img {
            transition: transform 0.5s ease;
        }

        .book-card:hover .book-img-container img {
            transform: scale(1.1);
        }

        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 6px 12px;
            border-radius: 8px;
            backdrop-filter: blur(8px);
            background: rgba(102, 126, 234, 0.85);
            color: white;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
        }

        .btn-pinjam {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-pinjam:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        .btn-pinjam:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-pinjam.added {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stock-info {
            font-size: 13px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .search-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 999;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-notification.success {
            border-left: 4px solid #10b981;
        }

        .toast-notification.error {
            border-left: 4px solid #ef4444;
        }

        /* Quantity Modal Styles */
        .quantity-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .quantity-modal.active {
            display: flex;
        }

        .quantity-modal-content {
            background: white;
            border-radius: 16px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        .quantity-modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 10px;
        }

        .quantity-modal-subtitle {
            font-size: 14px;
            color: #636e72;
            margin-bottom: 25px;
        }

        .quantity-form-group {
            margin-bottom: 20px;
        }

        .quantity-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .quantity-controls-modal {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f8fafc;
            padding: 15px;
            border-radius: 12px;
            border: 2px solid #edf2f7;
        }

        .qty-btn-modal {
            background: #6366f1;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn-modal:hover {
            background: #4f46e5;
            transform: scale(1.1);
        }

        .qty-input-modal {
            flex: 1;
            text-align: center;
            border: 1px solid #edf2f7;
            padding: 10px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }

        .qty-max-info {
            font-size: 12px;
            color: #718096;
            margin-top: 8px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn-modal-confirm {
            flex: 1;
            background: #6366f1;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-modal-confirm:hover {
            background: #4f46e5;
            transform: translateY(-2px);
        }

        .btn-modal-cancel {
            flex: 1;
            background: #e2e8f0;
            color: #2d3748;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-modal-cancel:hover {
            background: #cbd5e1;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <div class="container-fluid py-4 px-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold text-dark mb-1">E-Katalog Perpustakaan</h2>
                <p class="text-muted">Temukan pengetahuan dalam genggamanmu.</p>
            </div>
            <div class="col-md-6">
                <div class="search-section d-flex align-items-center"><i class="bi bi-search text-muted me-2"></i><input
                        type="text" class="form-control border-0 shadow-none"
                        placeholder="Cari judul buku atau kategori..."></div>
            </div>
        </div>
        <div class="row g-4">
            @forelse($books as $buku)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card book-card border-0 shadow-sm h-100">
                        <div class="book-img-container"><span
                                class="category-badge">{{ $buku->category->name ?? 'Umum' }}</span>
                            @if($buku->gambar)
                                <img src="{{ asset('storage/' . $buku->gambar) }}" class="w-100 h-100 object-fit-cover"
                            alt="{{ $buku->nama_buku }}">@else <div
                                    class="w-100 h-100 d-flex align-items-center justify-content-center bg-light"><i
                                class="bi bi-book text-muted opacity-25" style="font-size: 4rem;"></i></div>@endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-2 text-truncate-2" style="height: 3rem; line-height: 1.5rem;">
                                {{ $buku->nama_buku }}
                            </h5>
                            <div class="d-flex align-items-center mb-4 mt-auto">
                                <div class="stock-info"><i class="bi bi-box-seam"></i><span>Stok:
                                        <strong>{{ $buku->stock }}</strong></span></div>
                            </div><a href="#" class="btn btn-pinjam text-white w-100 shadow-sm add-to-cart-btn"
                                data-book-id="{{ $buku->id }}" data-book-title="{{ $buku->nama_buku }}"><i
                                    class="bi bi-plus-circle me-2"></i>Tambah ke Keranjang </a>
                        </div>
                    </div>
            </div>@empty <div class="col-12 py-5 text-center">
                <div class="mb-3"><i class="bi bi-journal-x text-muted opacity-25" style="font-size: 5rem;"></i></div>
                <h4 class="text-muted">Maaf, koleksi buku belum tersedia.</h4>
                <p class="text-muted">Silakan hubungi pustakawan atau admin.</p>
            </div>@endforelse
        </div>
    </div>

    <!-- Quantity Modal -->
    <div class="quantity-modal" id="quantityModal">
        <div class="quantity-modal-content">
            <div class="quantity-modal-title" id="modalTitle">Buku</div>
            <div class="quantity-modal-subtitle" id="modalSubtitle">Berapa banyak buku yang ingin Anda pinjam?</div>
            
            <form id="quantityForm">
                <div class="quantity-form-group">
                    <label class="quantity-label">Jumlah Peminjaman</label>
                    <div class="quantity-controls-modal">
                        <button type="button" class="qty-btn-modal" id="qtyMinus">−</button>
                        <input type="number" class="qty-input-modal" id="qtyInput" value="1" min="1">
                        <button type="button" class="qty-btn-modal" id="qtyPlus">+</button>
                    </div>
                    <div class="qty-max-info" id="maxStockLabel">Stok Tersedia: 0</div>
                    <div class="qty-max-info" id="qtyInfo">Sisa stok: 0</div>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" class="btn-modal-confirm" id="confirmBtn">
                        <i class="bi bi-check-circle me-2"></i>Tambahkan
                    </button>
                    <button type="button" class="btn-modal-cancel" id="cancelBtn">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script> // Quantity modal functionality
        const quantityModal = document.getElementById('quantityModal');
        const qtyInput = document.getElementById('qtyInput');
        const qtyMinus = document.getElementById('qtyMinus');
        const qtyPlus = document.getElementById('qtyPlus');
        const confirmBtn = document.getElementById('confirmBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const maxStockLabel = document.getElementById('maxStockLabel');
        const qtyInfo = document.getElementById('qtyInfo');
        const modalTitle = document.getElementById('modalTitle');
        const modalSubtitle = document.getElementById('modalSubtitle');

        let currentBookId = null;
        let maxStock = 0;

        // Handle Add to Cart button clicks - show modal instead of direct add
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                currentBookId = this.dataset.bookId;
                const bookTitle = this.dataset.bookTitle;

                // Get max stock from the data attribute or from page
                const bookCard = this.closest('.book-card');
                const stockSpan = bookCard.querySelector('.stock-info strong');
                maxStock = parseInt(stockSpan.textContent);

                // Reset modal
                qtyInput.value = 1;
                qtyInput.max = maxStock;
                qtyInput.min = 1;

                maxStockLabel.textContent = `Stok Tersedia: $ {
                                maxStock
                            }

                            `;

                modalTitle.textContent = `$ {
                                bookTitle
                            }

                            `;
                modalSubtitle.textContent = 'Berapa banyak buku yang ingin Anda pinjam?';
                updateQtyInfo();

                // Show modal
                quantityModal.classList.add('active');
            });
        });

        // Quantity +/- handlers in modal
        qtyMinus.addEventListener('click', function (e) {
            e.preventDefault();

            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                updateQtyInfo();
            }
        });

        qtyPlus.addEventListener('click', function (e) {
            e.preventDefault();

            if (parseInt(qtyInput.value) < maxStock) {
                qtyInput.value = parseInt(qtyInput.value) + 1;
                updateQtyInfo();
            }
        });

        // Cancel button
        cancelBtn.addEventListener('click', function (e) {
            e.preventDefault();
            quantityModal.classList.remove('active');
            currentBookId = null;
        });

        // Close modal when clicking outside
        quantityModal.addEventListener('click', function (e) {
            if (e.target === quantityModal) {
                quantityModal.classList.remove('active');
                currentBookId = null;
            }
        });

        // Form submission
        document.getElementById('quantityForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (!currentBookId) return;

            const quantity = parseInt(qtyInput.value);

            if (quantity < 1 || quantity > maxStock) {
                showToast('Jumlah tidak valid', 'error');
                return;
            }

            // Close modal
            quantityModal.classList.remove('active');

            // Send AJAX request with quantity
            fetch('{{ route("peminjam.cart.add") }}', {

                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }

                ,
                body: JSON.stringify({
                    book_id: currentBookId,
                    quantity: quantity
                })

            }).then(response => response.json()).then(data => {
                if (data.success) {

                    // Show success message
                    showToast(`✓ $ {
                                quantity
                            }

                            × buku ditambahkan ke keranjang`, 'success');

                    // Update cart badge in top nav
                    updateCartBadgeFromTop();

                    // Change button appearance
                    const addBtn = document.querySelector(`[data-book-id="${currentBookId}"]`);

                    if (addBtn) {
                        addBtn.classList.add('added');
                        addBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Sudah Ditambahkan';
                        addBtn.disabled = true;
                    }
                }

                else {
                    // Show error message
                    showToast(data.message || 'Gagal menambahkan ke keranjang', 'error');
                }

                currentBookId = null;

            }).catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                currentBookId = null;
            });
        });

        // Update quantity info display
        function updateQtyInfo() {
            const qty = parseInt(qtyInput.value);
            const remaining = maxStock - qty;

            if (remaining > 0) {
                qtyInfo.textContent = `Sisa stok: $ {
                        remaining
                    }

                    `;
            }

            else if (remaining === 0) {
                qtyInfo.textContent = 'Stok akan habis';
            }
        }

        // Update cart badge in top navigation
        function updateCartBadgeFromTop() {
            fetch('{{ route("peminjam.cart.mini") }}').then(response => response.json()).then(data => {
                const badge = document.getElementById('cartBadgeTop');

                if (badge) {
                    badge.textContent = data.count;
                }
            });
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');

            toast.className = `toast-notification $ {
                    type
                }

                `;
            toast.textContent = message;

            document.body.appendChild(toast);

            // Auto remove after 4 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }

                , 4000);
        }

        // Add animation for sliding out
        const style = document.createElement('style');

        style.textContent = ` @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }

                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }

            `;
        document.head.appendChild(style);
</script>@endsection
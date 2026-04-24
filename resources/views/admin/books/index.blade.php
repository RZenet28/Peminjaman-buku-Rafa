@extends('layouts.app')

@section('title', 'Kelola Buku')

@section('content')
    <div class="container-fluid p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Kelola Buku</h2>
                <p class="text-muted mb-0">Manajemen katalog buku perpustakaan</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-2"></i>Tambah Buku
            </button>
        </div>

        <!-- Search & Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Cari judul buku atau kategori..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-funnel me-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="row g-4">
            @forelse($books as $book)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <!-- Book Cover -->
                        <div class="position-relative"
                            style="height: 280px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            @if($book->gambar)
                                <img src="{{ asset('storage/' . $book->gambar) }}" class="w-100 h-100 object-fit-cover"
                                    alt="{{ $book->nama_buku }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="bi bi-book text-white" style="font-size: 80px; opacity: 0.5;"></i>
                                </div>
                            @endif

                            <!-- Stock Badge -->
                            <div class="position-absolute top-0 end-0 m-3">
                                @if($book->stock > 10)
                                    <span class="badge bg-success">{{ $book->stock }} Tersedia</span>
                                @elseif($book->stock > 0)
                                    <span class="badge bg-warning">{{ $book->stock }} Tersisa</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </div>
                        </div>

                        <!-- Book Info -->
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary small">
                                    <i class="bi bi-tag me-1"></i>{{ $book->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>

                            <h6 class="fw-bold mb-2 text-truncate" title="{{ $book->nama_buku }}">
                                {{ $book->nama_buku }}
                            </h6>

                            @if($book->pengarang || $book->penerbit || $book->tahun)
                                <div class="small text-muted mb-2">
                                    @if($book->pengarang)
                                        <div><i class="bi bi-person me-1"></i>{{ $book->pengarang }}</div>
                                    @endif
                                    @if($book->penerbit)
                                        <div><i class="bi bi-building me-1"></i>{{ $book->penerbit }}</div>
                                    @endif
                                    @if($book->tahun)
                                        <div><i class="bi bi-calendar me-1"></i>{{ $book->tahun }}</div>
                                    @endif
                                </div>
                            @endif

                            @if($book->deskripsi)
                                <p class="text-muted small mb-3"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $book->deskripsi }}
                                </p>
                            @endif

                            {{-- <!-- Denda Info -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span class="text-muted">
                                        <i class="bi bi-exclamation-circle me-1"></i>Denda Hilang:
                                    </span>
                                    <strong class="text-danger">Rp
                                        {{ number_format($book->denda_hilang, 0, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="text-muted">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Denda Rusak:
                                    </span>
                                    <strong class="text-warning">Rp
                                        {{ number_format($book->denda_rusak, 0, ',', '.') }}</strong>
                                </div>
                            </div> --}}

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary flex-fill" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $book->id }}">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteBook({{ $book->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $book->id }}" action="{{ route('admin.books.destroy', $book->id) }}"
                                method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $book->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <form method="POST" action="{{ route('admin.books.update', $book->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-header border-0 pb-0">
                                    <div>
                                        <h5 class="modal-title fw-bold">Edit Buku</h5>
                                        <p class="text-muted small mb-0">Update informasi buku</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-book me-2 text-primary"></i>Nama Buku
                                            </label>
                                            <input type="text" name="nama_buku" class="form-control"
                                                value="{{ $book->nama_buku }}" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-tag me-2 text-primary"></i>Kategori
                                            </label>
                                            <select name="category_id" class="form-select" required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-person me-2 text-primary"></i>Pengarang
                                            </label>
                                            <input type="text" name="pengarang" class="form-control"
                                                value="{{ $book->pengarang }}" placeholder="Nama pengarang...">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-building me-2 text-primary"></i>Penerbit
                                            </label>
                                            <input type="text" name="penerbit" class="form-control"
                                                value="{{ $book->penerbit }}" placeholder="Nama penerbit...">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-calendar me-2 text-primary"></i>Tahun
                                            </label>
                                            <input type="number" name="tahun" class="form-control"
                                                value="{{ $book->tahun }}" placeholder="2024" min="1900" max="{{ date('Y') }}">
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-text-paragraph me-2 text-primary"></i>Deskripsi
                                            </label>
                                            <textarea name="deskripsi" class="form-control" rows="3"
                                                placeholder="Deskripsi singkat buku...">{{ $book->deskripsi }}</textarea>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-box-seam me-2 text-primary"></i>Stock
                                            </label>
                                            <input type="number" name="stock" class="form-control" value="{{ $book->stock }}"
                                                required>
                                        </div>

                                        {{-- <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-exclamation-circle me-2 text-danger"></i>Denda Hilang
                                            </label>
                                            <input type="number" name="denda_hilang" class="form-control"
                                                value="{{ $book->denda_hilang }}" required>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Denda Rusak
                                            </label>
                                            <input type="number" name="denda_rusak" class="form-control"
                                                value="{{ $book->denda_rusak }}" required>
                                        </div> --}}

                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-image me-2 text-primary"></i>Gambar Buku
                                            </label>

                                            @if($book->gambar)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $book->gambar) }}" class="rounded shadow-sm"
                                                        style="width: 120px; height: 160px; object-fit: cover;">
                                                </div>
                                            @endif

                                            <input type="file" name="gambar" class="form-control" accept="image/*">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Kosongkan jika tidak ingin mengganti gambar
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Update Buku
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">Belum ada data buku</h5>
                            <p class="text-muted mb-3">Tambahkan buku pertama Anda</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Buku
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $books->withQueryString()->links() }}
            </div>
        @endif

    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold">Tambah Buku Baru</h5>
                            <p class="text-muted small mb-0">Isi form dibawah untuk menambahkan buku</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-book me-2 text-primary"></i>Nama Buku
                                </label>
                                <input type="text" name="nama_buku" class="form-control" placeholder="Masukkan judul buku"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-2 text-primary"></i>Kategori
                                </label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person me-2 text-primary"></i>Pengarang
                                </label>
                                <input type="text" name="pengarang" class="form-control"
                                    placeholder="Nama pengarang...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-2 text-primary"></i>Penerbit
                                </label>
                                <input type="text" name="penerbit" class="form-control"
                                    placeholder="Nama penerbit...">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar me-2 text-primary"></i>Tahun
                                </label>
                                <input type="number" name="tahun" class="form-control"
                                    placeholder="2024" min="1900" max="{{ date('Y') }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-text-paragraph me-2 text-primary"></i>Deskripsi
                                </label>
                                <textarea name="deskripsi" class="form-control" rows="3"
                                    placeholder="Deskripsi singkat buku..."></textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-box-seam me-2 text-primary"></i>Stock
                                </label>
                                <input type="number" name="stock" class="form-control" placeholder="0" required>
                            </div>

                            {{-- <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-exclamation-circle me-2 text-danger"></i>Denda Hilang
                                </label>
                                <input type="number" name="denda_hilang" class="form-control" placeholder="0" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Denda Rusak
                                </label>
                                <input type="number" name="denda_rusak" class="form-control" placeholder="0" required>
                            </div> --}}

                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-image me-2 text-primary"></i>Gambar Buku
                                </label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Format: JPG, PNG (Max 2MB)
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }

        .object-fit-cover {
            object-fit: cover;
        }
    </style>

@endsection

@push('scripts')
    <script>
        function deleteBook(bookId) {
            Swal.fire({
                title: 'Hapus Buku',
                text: 'Apakah Anda yakin ingin menghapus buku ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + bookId).submit();
                }
            });
        }
    </script>
@endpush
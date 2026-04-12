@extends('layouts.peminjam')

@section('title', 'Dashboard Peminjam')

@section('content')
    <div class="container-fluid p-4">

        <!-- Welcome Banner -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center text-white">
                            <div>
                                <h2 class="fw-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                                <p class="mb-0 opacity-75">
                                    <i class="bi bi-mortarboard me-2"></i>
                                    {{ Auth::user()->kelas ?? 'Peminjam' }} | NIS: {{ Auth::user()->no_identitas ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <!-- Buku Dipinjam -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-3"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="bi bi-book text-white" style="font-size: 24px;"></i>
                            </div>
                            @if($bukuTerlambat > 0)
                                <span class="badge bg-danger">{{ $bukuTerlambat }} Terlambat</span>
                            @else
                                <span class="badge" style="background: #f3f4f6; color: #667eea;">Aktif</span>
                            @endif
                        </div>
                        <h6 class="text-muted small mb-1">Sedang Dipinjam</h6>
                        <h2 class="fw-bold mb-0">{{ $bukuDipinjam }}</h2>
                        <small class="text-muted">dari 3 maksimal</small>
                    </div>
                </div>
            </div>

            <!-- Total Pinjam -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-3"
                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <i class="bi bi-graph-up text-white" style="font-size: 24px;"></i>
                            </div>
                            <span class="badge" style="background: #fef3f2; color: #f5576c;">Total</span>
                        </div>
                        <h6 class="text-muted small mb-1">Total Peminjaman</h6>
                        <h2 class="fw-bold mb-0">{{ $totalPeminjaman }}</h2>
                        <small class="text-muted">sepanjang waktu</small>
                    </div>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-3"
                                style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                <i class="bi bi-exclamation-triangle text-white" style="font-size: 24px;"></i>
                            </div>
                            @if($bukuTerlambat > 0)
                                <span class="badge" style="background: #dcfce7; color: #dc2626;">Perhatian</span>
                            @else
                                <span class="badge" style="background: #dcfce7; color: #16a34a;">Aman</span>
                            @endif
                        </div>
                        <h6 class="text-muted small mb-1">Keterlambatan</h6>
                        <h2 class="fw-bold mb-0">{{ $bukuTerlambat }}</h2>
                        <small class="text-muted">buku terlambat</small>
                    </div>
                </div>
            </div>

            <!-- Profil -->
            <div class="col-12 col-sm-6 col-xl-3">
                <a href="{{ route('peminjam.profile') }}" class="card border-0 shadow-sm h-100 text-decoration-none"
                    style="color: inherit;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-3"
                                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <i class="bi bi-person text-white" style="font-size: 24px;"></i>
                            </div>
                            <span class="badge" style="background: #e0f2fe; color: #0284c7;">Profil</span>
                        </div>
                        <h6 class="text-muted small mb-1">Lihat Profil</h6>
                        <p class="mb-0">
                            <span class="fw-bold">Riwayat</span>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </p>
                        <small class="text-muted">Kelola akun Anda</small>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-lightning-charge text-primary me-2"></i>
                            Aksi Cepat
                        </h5>
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <a href="/peminjam/daftar-buku"
                                    class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2 hover-lift">
                                    <i class="bi bi-book-fill" style="font-size: 24px;"></i>
                                    <span class="small fw-semibold">Daftar Buku</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-4">
                                <a href="/peminjam/ajukan-peminjaman"
                                    class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-2 hover-lift">
                                    <i class="bi bi-plus-circle-fill" style="font-size: 24px;"></i>
                                    <span class="small fw-semibold">Ajukan Peminjaman</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-4">
                                <a href="/peminjam/kembalikan-buku"
                                    class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center gap-2 hover-lift">
                                    <i class="bi bi-arrow-return-left" style="font-size: 24px;"></i>
                                    <span class="small fw-semibold">Kembalikan Buku</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Current Borrowings & Book Recommendations -->
        <div class="row g-4 mb-4">

            <!-- Current Borrowings -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-book-half text-primary me-2"></i>
                                Buku yang Sedang Dipinjam
                            </h5>
                            <a href="{{ route('peminjam.kembali') }}"
                                class="text-primary text-decoration-none fw-semibold small">
                                Lihat Semua <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        @if($peminjamanAktif->count() > 0)
                            <div class="list-group">
                                @foreach($peminjamanAktif as $peminjaman)
                                    <div class="list-group-item border-0 bg-light mb-2 rounded">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $peminjaman->buku->nama_buku ?? 'Buku Dihapus' }}</h6>
                                                <p class="text-muted small mb-0">ISBN: {{ $peminjaman->buku->isbn ?? '-' }}</p>
                                            </div>
                                            @if(\Carbon\Carbon::parse($peminjaman->tanggal_kembali)->isPast())
                                                <span class="badge bg-danger">Terlambat</span>
                                            @else
                                                <span class="badge bg-info">Dipinjam</span>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Kembali:
                                                <strong>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</strong>
                                            </small>
                                            @if(\Carbon\Carbon::parse($peminjaman->tanggal_kembali)->isPast())
                                                <small class="text-danger fw-bold">
                                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(\Carbon\Carbon::now()) }}
                                                    hari terlambat
                                                </small>
                                            @else
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(\Carbon\Carbon::now()) }}
                                                    hari lagi
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 64px;"></i>
                                <p class="text-muted mt-3 mb-3">Belum ada buku yang dipinjam</p>
                                <a href="{{ route('peminjam.books.index') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search me-2"></i>Lihat Daftar Buku
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Book Recommendations -->
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-star text-warning me-2"></i>
                            Buku Populer
                        </h5>

                        @if($bukuPopuler->count() > 0)
                            @foreach($bukuPopuler as $book)
                                <div class="d-flex gap-3 mb-3 p-2 rounded hover-bg" style="cursor: pointer;">
                                    <div class="bg-gradient rounded"
                                        style="width: 60px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="bi bi-book text-white" style="font-size: 24px;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1 small">{{ $book->nama_buku }}</h6>
                                        <p class="text-muted mb-2" style="font-size: 12px;">
                                            {{ $book->category->nama_kategori ?? 'Kategori' }}</p>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 10px;">
                                                <i class="bi bi-bookmark-check me-1"></i>{{ $book->nama_buku }}
                                            </span>
                                            @if($book->stock > 0)
                                                <span class="text-success small">
                                                    <i class="bi bi-check-circle me-1"></i>Tersedia
                                                </span>
                                            @else
                                                <span class="text-danger small">
                                                    <i class="bi bi-x-circle me-1"></i>Habis
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="text-center mt-3">
                            <a href="{{ route('peminjam.books.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua Buku
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .hover-bg:hover {
            background: #f9fafb;
        }
    </style>
@endsection
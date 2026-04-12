@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid p-4">

        <!-- Header Section -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h2 fw-bold mb-2">Dashboard Admin</h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-person-circle text-primary"></i>
                        Selamat datang, <strong>{{ auth()->user()->name }}</strong>
                    </p>
                </div>
                <div>
                    <span class="badge bg-light text-dark border p-2">
                        <i class="bi bi-calendar3 text-muted"></i>
                        {{ now()->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Buku -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                                <i class="bi bi-book text-white" style="font-size: 24px;"></i>
                            </div>
                            @if($lowStockBooks->count() > 0)
                                <span class="badge" style="background: #fecaca; color: #dc2626;">{{ $lowStockBooks->count() }} Low Stock</span>
                            @else
                                <span class="badge" style="background: #eef2ff; color: #6366f1;">+12%</span>
                            @endif
                        </div>
                        <h6 class="text-muted small mb-1">Total Buku</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalBooks) }}</h2>
                    </div>
                </div>
            </div>

            <!-- Sedang Dipinjam -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="bi bi-journal-text text-white" style="font-size: 24px;"></i>
                            </div>
                            <span class="badge" style="background: #d1fae5; color: #059669;">Active</span>
                        </div>
                        <h6 class="text-muted small mb-1">Sedang Dipinjam</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($borrowingsActive) }}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Pengguna -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);">
                                <i class="bi bi-people text-white" style="font-size: 24px;"></i>
                            </div>
                            <span class="badge" style="background: #f3e8ff; color: #9333ea;">+8%</span>
                        </div>
                        <h6 class="text-muted small mb-1">Total Pengguna</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalUsers) }}</h2>
                    </div>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);">
                                <i class="bi bi-clock-history text-white" style="font-size: 24px;"></i>
                            </div>
                            @if($borrowingsLate > 0)
                                <span class="badge" style="background: #ffe4e6; color: #e11d48;">Alert</span>
                            @else
                                <span class="badge" style="background: #d1fae5; color: #059669;">Safe</span>
                            @endif
                        </div>
                        <h6 class="text-muted small mb-1">Terlambat</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($borrowingsLate) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity -->
        <div class="row g-3 mb-4">
            <!-- Quick Actions -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-lightning-charge text-primary"></i>
                            Aksi Cepat
                        </h5>

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.index') }}"
                                class="btn btn-primary text-start d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-people me-2"></i>Kelola User</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                            <a href="{{ route('admin.books.index') }}"
                                class="btn btn-success text-start d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-book me-2"></i>Kelola Buku</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                            <a href="{{ route('admin.reporting.index') }}"
                                class="btn btn-warning text-start d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-bar-chart me-2"></i>Laporan</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-clock text-primary"></i>
                                Aktivitas Terbaru
                            </h5>
                            <a href="{{ route('admin.history.index') }}" class="text-primary fw-semibold small text-decoration-none">
                                Lihat Semua <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <div class="list-group list-group-flush">
                            @forelse($recentBorrowings as $borrowing)
                                <div class="list-group-item px-0">
                                    <div class="d-flex gap-3">
                                        @if($borrowing->status === 'pending')
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b, #d97706); flex-shrink: 0;">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                        @elseif($borrowing->status === 'dipinjam')
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669); flex-shrink: 0;">
                                                <i class="bi bi-check-lg"></i>
                                            </div>
                                        @elseif($borrowing->status === 'dikembalikan')
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #2563eb); flex-shrink: 0;">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </div>
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px; background: linear-gradient(135deg, #6b7280, #4b5563); flex-shrink: 0;">
                                                <i class="bi bi-x-lg"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <p class="mb-1 small">
                                                <strong>{{ $borrowing->user->name ?? 'User' }}</strong>
                                                @if($borrowing->status === 'pending')
                                                    mengajukan peminjaman
                                                @elseif($borrowing->status === 'dipinjam')
                                                    meminjam
                                                @elseif($borrowing->status === 'dikembalikan')
                                                    mengembalikan
                                                @else
                                                    menolak
                                                @endif
                                                <strong class="text-primary">"{{ $borrowing->buku->nama_buku ?? 'Buku' }}"</strong>
                                            </p>
                                            <p class="text-muted mb-0" style="font-size: 12px;">{{ $borrowing->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-3">
                                    <p class="text-muted mb-0">Tidak ada aktivitas terbaru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Books -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-graph-up text-primary"></i>
                        Buku Paling Populer
                    </h5>
                    <a href="{{ route('admin.books.index') }}" class="text-primary fw-semibold small text-decoration-none">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="row g-3">
                    @forelse($topBooks as $book)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card h-100"
                                style="background: linear-gradient(135deg, #eef2ff, #e0e7ff); border: 1px solid #c7d2fe;">
                                <div class="card-body">
                                    <div class="d-flex gap-3 align-items-center mb-3">
                                        <div class="rounded d-flex align-items-center justify-content-center text-white"
                                            style="width: 50px; height: 60px; background: linear-gradient(135deg, #6366f1, #4f46e5); flex-shrink: 0;">
                                            <i class="bi bi-book" style="font-size: 24px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 small">{{ $book->nama_buku }}</h6>
                                            <p class="text-muted mb-0" style="font-size: 11px;">{{ $book->category->nama_kategori ?? 'Kategori' }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center small">
                                        <span class="text-muted">Dipinjam:</span>
                                        <strong class="text-primary">{{ $book->peminjaman_count }}x</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted py-3">Belum ada buku yang dipinjam</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection
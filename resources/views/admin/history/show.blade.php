@extends('layouts.app')

@section('title', 'Detail Riwayat Peminjaman')

@section('content')
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.history.index') }}" class="btn btn-outline-secondary mb-3">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                <h2 class="fw-bold mb-1">Detail Riwayat Peminjaman</h2>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0">Informasi Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small text-muted">ID Peminjaman</label>
                                <p class="fw-bold">#{{ $borrowing->id }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Status</label>
                                <p>
                                    @if($borrowing->status === 'dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @elseif($borrowing->status === 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $borrowing->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small text-muted">Tanggal Pinjam</label>
                                <p class="fw-bold">{{ $borrowing->tanggal_pinjam->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Tanggal Kembali (Seharusnya)</label>
                                <p class="fw-bold">{{ $borrowing->tanggal_kembali->format('d M Y') }}</p>
                            </div>
                        </div>

                        @if($borrowing->tanggal_pengembalian)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small text-muted">Tanggal Pengembalian (Aktual)</label>
                                    <p class="fw-bold">{{ $borrowing->tanggal_pengembalian->format('d M Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Keterlambatan</label>
                                    @if($borrowing->tanggal_pengembalian > $borrowing->tanggal_kembali)
                                        <p class="fw-bold text-warning">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            {{ $borrowing->tanggal_pengembalian->diffInDays($borrowing->tanggal_kembali) }} hari
                                        </p>
                                    @else
                                        <p class="fw-bold text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Tepat Waktu
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($borrowing->denda)
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="small text-muted">Denda</label>
                                    <p class="fw-bold text-danger">Rp {{ number_format($borrowing->denda) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($borrowing->catatan)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="fw-bold mb-0">Catatan</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ $borrowing->catatan }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Peminjam Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0">Informasi Peminjam</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">{{ $borrowing->user->name }}</h6>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-envelope me-2"></i>{{ $borrowing->user->email }}
                        </p>
                        <p class="small text-muted mb-0">
                            <i class="bi bi-tag me-2"></i>{{ $borrowing->user->role }}
                        </p>
                    </div>
                </div>

                <!-- Buku Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0">Informasi Buku</h5>
                    </div>
                    <div class="card-body">
                        @if($borrowing->buku->gambar)
                            <img src="{{ asset('storage/' . $borrowing->buku->gambar) }}"
                                alt="{{ $borrowing->buku->nama_buku }}" class="img-fluid rounded mb-3"
                                style="max-height: 150px;">
                        @endif
                        <h6 class="fw-bold mb-2">{{ $borrowing->buku->nama_buku }}</h6>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-tag me-2"></i>{{ $borrowing->buku->category->name ?? '-' }}
                        </p>
                        <p class="small text-muted mb-0">
                            <i class="bi bi-book me-2"></i>Stok: {{ $borrowing->buku->stock }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
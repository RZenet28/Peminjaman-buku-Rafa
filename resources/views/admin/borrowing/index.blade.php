@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Kelola Peminjaman</h2>
                <p class="text-muted mb-0">Manajemen permintaan peminjaman buku</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted small mb-1">Menunggu Persetujuan</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                            </div>
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 28px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted small mb-1">Sedang Dipinjam</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['dipinjam'] }}</h3>
                            </div>
                            <i class="bi bi-book-half text-info" style="font-size: 28px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted small mb-1">Dikembalikan</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['dikembalikan'] }}</h3>
                            </div>
                            <i class="bi bi-check-circle text-success" style="font-size: 28px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted small mb-1">Ditolak</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['ditolak'] }}</h3>
                            </div>
                            <i class="bi bi-x-circle text-danger" style="font-size: 28px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search & Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama peminjam atau buku..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam
                            </option>
                            <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>
                                Dikembalikan</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search me-1"></i>Cari</button>
                        <a href="{{ route('admin.borrowing.index') }}" class="btn btn-outline-secondary"><i
                                class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Borrowing Table -->
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            <tr>
                                <td>
                                    <strong>{{ $borrowing->user->name ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ $borrowing->user->email ?? '-' }}</small>
                                </td>
                                <td>{{ $borrowing->buku->nama_buku ?? '-' }}</td>
                                <td>{{ $borrowing->tanggal_pinjam->format('d M Y') }}</td>
                                <td>{{ $borrowing->tanggal_kembali->format('d M Y') }}</td>
                                <td>
                                    @if($borrowing->status === 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($borrowing->status === 'dipinjam')
                                        <span class="badge bg-info">Dipinjam</span>
                                    @elseif($borrowing->status === 'dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->status === 'pending')
                                        <form action="{{ route('admin.borrowing.approve', $borrowing->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Setujui peminjaman ini?')"><i
                                                    class="bi bi-check"></i></button>
                                        </form>
                                        <form action="{{ route('admin.borrowing.reject', $borrowing->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tolak peminjaman ini?')"><i class="bi bi-x"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">Tidak ada data peminjaman</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $borrowings->links() }}
            </div>
        </div>
    </div>
@endsection
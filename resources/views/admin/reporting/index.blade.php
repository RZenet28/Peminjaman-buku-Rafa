@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Laporan Peminjaman</h2>
                <p class="text-muted mb-0">Analisis dan statistik peminjaman buku</p>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-2"></i>Tampilkan
                            Laporan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">Total Peminjaman</h6>
                        <h3 class="fw-bold">{{ $totalBorrowings }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">Peminjaman Selesai</h6>
                        <h3 class="fw-bold text-success">{{ $completedBorrowings }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">Ditolak</h6>
                        <h3 class="fw-bold text-danger">{{ $rejectedBorrowings }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">Total Denda</h6>
                        <h3 class="fw-bold text-warning">Rp {{ number_format($totalFines) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Books & Users -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0">Buku Paling Dipinjam</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Buku</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topBooks as $book)
                                        <tr>
                                            <td>{{ $book->nama_buku }}</td>
                                            <td class="text-end"><span
                                                    class="badge bg-primary">{{ $book->peminjaman_count }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0">Peminjam Paling Aktif</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th class="text-end">Peminjaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td class="text-end"><span
                                                    class="badge bg-info">{{ $user->peminjaman_count }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Button -->
        <div class="mb-4">
            <form method="GET" action="{{ route('admin.reporting.export') }}" class="d-inline">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Export CSV
                </button>
            </form>
        </div>
    </div>
@endsection
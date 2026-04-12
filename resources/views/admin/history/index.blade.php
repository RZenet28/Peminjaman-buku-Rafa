@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Riwayat Peminjaman</h2>
                <p class="text-muted mb-0">Lihat semua riwayat peminjaman buku</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">Selesai</h6>
                        <h3 class="fw-bold text-success">{{ $stats['completed'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">Tepat Waktu</h6>
                        <h3 class="fw-bold text-info">{{ $stats['on_time'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">Terlambat</h6>
                        <h3 class="fw-bold text-warning">{{ $stats['late'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">Ditolak</h6>
                        <h3 class="fw-bold text-danger">{{ $stats['rejected'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari peminjam atau buku..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="all">Semua Status</option>
                            <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>
                                Dikembalikan</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search me-1"></i>Cari</button>
                        <a href="{{ route('admin.history.index') }}" class="btn btn-outline-secondary"><i
                                class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- History Table -->
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali (Seharusnya)</th>
                            <th>Tanggal Pengembalian (Aktual)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->user->name ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ $item->user->email ?? '-' }}</small>
                                </td>
                                <td>{{ $item->buku->nama_buku ?? '-' }}</td>
                                <td>{{ $item->tanggal_pinjam->format('d M Y') }}</td>
                                <td>{{ $item->tanggal_kembali->format('d M Y') }}</td>
                                <td>
                                    @if($item->tanggal_pengembalian)
                                        <span class="text-muted">{{ $item->tanggal_pengembalian->format('d M Y') }}</span>
                                        @if($item->status === 'dikembalikan')
                                            @if($item->tanggal_pengembalian > $item->tanggal_kembali)
                                                <span class="badge bg-warning">Terlambat
                                                    {{ $item->tanggal_pengembalian->diffInDays($item->tanggal_kembali) }} hari</span>
                                            @else
                                                <span class="badge bg-success">Tepat Waktu</span>
                                            @endif
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @elseif($item->status === 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.history.show', $item->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted">Tidak ada data riwayat peminjaman</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $history->links() }}
            </div>
        </div>
    </div>
@endsection
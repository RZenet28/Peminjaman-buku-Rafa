@extends('layouts.app')

@section('title', 'Menyetujui Pengembalian Buku')

@section('content')
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Menyetujui Pengembalian Buku</h2>
                <p class="text-muted mb-0">Kelola pengembalian buku yang menunggu persetujuan</p>
            </div>
            <div class="badge bg-warning text-dark" style="font-size: 1rem;">
                {{ $pendingCount }} Menunggu
            </div>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi Kesalahan!</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search & Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama peminjam atau judul buku..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-search me-1"></i>Cari
                            </button>
                            <a href="{{ route('admin.borrowing.return_approval') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pending Returns Table -->
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Peminjam</th>
                            <th>Kontak</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Seharusnya Kembali</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Catatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingReturns as $return)
                            <tr>
                                <td>
                                    <strong>{{ $return->user->name ?? '-' }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $return->user->email ?? '-' }}</small><br>
                                    <small class="text-muted">{{ $return->user->phone ?? '-' }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $return->buku->nama_buku ?? '-' }}</strong>
                                    </div>
                                    <small class="text-muted">
                                        @if($return->buku && $return->buku->penulis)
                                            Penulis: {{ $return->buku->penulis }}
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $return->tanggal_pinjam->format('d M Y') }}</td>
                                <td>
                                    {{ $return->tanggal_kembali->format('d M Y') }}
                                    @if($return->tanggal_kembali < \Carbon\Carbon::now() && !$return->tanggal_pengembalian)
                                        <br><span class="badge bg-danger">TERLAMBAT</span>
                                    @endif
                                </td>
                                <td>{{ $return->tanggal_pengembalian ? $return->tanggal_pengembalian->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    @if($return->catatan)
                                        <small>{{ Str::limit($return->catatan, 30) }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <form action="{{ route('admin.borrowing.approve_return', $return->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" title="Setujui pengembalian"
                                                onclick="return confirm('Setujui pengembalian buku ini? Stok buku akan bertambah.')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.borrowing.reject_return', $return->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger" title="Tolak pengembalian"
                                                onclick="return confirm('Tolak pengembalian ini? Status akan kembali ke dipinjam.')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="text-muted mt-3 mb-0">Tidak ada pengembalian buku yang menunggu persetujuan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pendingReturns->count() > 0)
                <div class="card-footer bg-white">
                    {{ $pendingReturns->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

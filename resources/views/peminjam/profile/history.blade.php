@extends('layouts.peminjam')

@section('title', 'Profil dan Riwayat Peminjaman')

@section('content')
    <style>
        /* Modern UI Custom CSS */
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #6366f1;
            --bg-light: #f8fafc;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            --border-radius: 16px;
        }

        body {
            background-color: var(--bg-light);
            color: #1e293b;
        }

        .container-fluid {
            max-width: 1300px;
        }

        /* Card Styling */
        .card {
            border: none !important;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease;
        }

        .card-header {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1.25rem;
        }

        /* Avatar & Profile */
        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .avatar-circle {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        /* Table Styling */
        .table-modern thead th {
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            border-top: none;
            padding: 1rem;
        }

        .table-modern tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Status Badges */
        .badge-modern {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Stats Card */
        .stat-box {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            border-color: var(--primary-color);
            background: #fdfdff;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-outline-primary {
            border-radius: 10px;
            border-color: #e2e8f0;
            color: #64748b;
        }

        .btn-outline-primary:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        /* Custom Alert */
        .alert-denda {
            background-color: #fffbeb;
            border: 1px dashed #f59e0b;
            border-radius: 12px;
        }
    </style>

    <div class="container-fluid p-4">
        <div class="row mb-5 align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold tracking-tight mb-1">Profil Saya</h2>
                <p class="text-muted">Akses informasi akun dan pantau aktivitas peminjaman buku Anda.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('peminjam.dashboard') }}" class="btn btn-outline-primary">
                    <i class="bi bi-grid me-2"></i>Dashboard
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="avatar-wrapper mb-3">
                                <div class="avatar-circle mx-auto d-flex align-items-center justify-content-center"
                                    style="width: 90px; height: 90px; border-radius: 28px;">
                                    <i class="bi bi-person-fill text-white" style="font-size: 45px;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                            <p class="text-muted small">{{ $user->email }}</p>
                            <span class="badge bg-soft-primary text-primary px-3 py-2" style="background: #eef2ff;">
                                {{ strtoupper($user->role) }}
                            </span>
                        </div>

                        {{-- <div class="info-group mb-4">
                            <label class="text-uppercase x-small fw-bold text-muted mb-2 d-block"
                                style="font-size: 0.7rem;">Informasi Dasar</label>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">ID</span>
                                <span class="fw-medium">{{ $user->no_identitas ?? '—' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Kelas</span>
                                <span class="fw-medium">{{ $user->kelas ?? '—' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Telepon</span>
                                <span class="fw-medium">{{ $user->no_telpon ?? '—' }}</span>
                            </div>
                        </div> --}}

                        <div class="stats-grid row g-2 mb-4">
                            <div class="col-6">
                                <div class="stat-box p-3 rounded-4 text-center">
                                    <span class="d-block text-primary fw-bold h5 mb-0">{{ $stats['total'] }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">Total Pinjam</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box p-3 rounded-4 text-center">
                                    <span class="d-block text-indigo fw-bold h5 mb-0"
                                        style="color: #6366f1;">{{ $stats['active'] }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">Aktif</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-denda p-3 mb-4 text-center">
                            <span class="d-block text-muted small mb-1">Tunggakan Denda</span>
                            <h4 class="fw-bold mb-0 text-warning">Rp {{ number_format($totalFines, 0, ',', '.') }}</h4>
                        </div>

                        {{-- <div class="d-grid gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="bi bi-pencil-square me-2"></i>Edit Profil
                            </button>
                            <button class="btn btn-light text-muted" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">
                                <i class="bi bi-shield-lock me-2"></i>Keamanan
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Riwayat Aktivitas</h5>
                        <span class="text-muted small">Menampilkan {{ $history->count() }} data terbaru</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Informasi Buku</th>
                                        <th>Waktu Pinjam</th>
                                        <th>Status</th>
                                        <th class="text-end">Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($history as $loan)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $loan->buku->nama_buku ?? 'Buku Dihapus' }}
                                                </div>
                                                <div class="text-muted x-small" style="font-size: 0.75rem;">ISBN:
                                                    {{ $loan->buku->isbn ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <div class="small fw-medium">
                                                    {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</div>
                                                <div class="x-small text-muted" style="font-size: 0.7rem;">
                                                    Tempo: {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($loan->status === 'dikembalikan')
                                                    <span class="badge badge-modern bg-success-subtle text-success"
                                                        style="background: #dcfce7;">Selesai</span>
                                                @elseif($loan->status === 'dipinjam')
                                                    <span class="badge badge-modern bg-primary-subtle text-primary"
                                                        style="background: #e0e7ff;">Berjalan</span>
                                                @elseif($loan->status === 'ditolak')
                                                    <span class="badge badge-modern bg-secondary-subtle text-muted"
                                                        style="background: #f1f5f9;">Ditolak</span>
                                                @else
                                                    <span class="badge badge-modern bg-warning-subtle text-warning"
                                                        style="background: #fef3c7;">Proses</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($loan->denda > 0)
                                                    <span class="fw-bold text-danger">Rp
                                                        {{ number_format($loan->denda, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <img src="https://illustrations.popsy.co/gray/empty-states.svg" alt="Empty"
                                                    style="width: 150px;" class="mb-3">
                                                <p class="text-muted">Belum ada riwayat peminjaman ditemukan.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($history->hasPages())
                        <div class="card-footer bg-white border-0 py-3">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modals remain functionally the same but can use the new .btn-primary and .form-control classes --}}
@endsection
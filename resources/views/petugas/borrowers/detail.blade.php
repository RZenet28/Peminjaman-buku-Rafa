@extends('layouts.petugas')

@section('content')
<style>
    :root {
        --primary: #10b981;
        --secondary: #3b82f6;
        --purple: #8b5cf6;
        --bg-page: #f8fafc;
        --white: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border: #e2e8f0;
    }

    .detail-wrapper {
        font-family: 'Inter', sans-serif;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        color: var(--text-main);
    }

    /* Header Navigation */
    .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1rem;
        transition: transform 0.2s;
    }
    .back-link:hover { transform: translateX(-5px); }

    .header-content { margin-bottom: 2.5rem; }
    .page-title { font-size: 2.25rem; font-weight: 800; color: #0f172a; margin: 0; }
    .page-subtitle { color: var(--text-muted); margin-top: 0.5rem; }

    /* Layout Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
    }

    /* Modern Cards */
    .glass-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #334155;
    }

    /* Profile Info List */
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-item:last-child { border-bottom: none; }
    .label { font-weight: 600; color: var(--text-muted); font-size: 0.9rem; }
    .value { font-weight: 700; color: var(--text-main); text-align: right; }

    /* Stats Box */
    .stat-card {
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
        margin-bottom: 1rem;
    }
    .stat-blue { background: #eff6ff; border: 1px solid #dbeafe; color: #1e40af; }
    .stat-green { background: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; }

    .stat-label { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; opacity: 0.8; }
    .stat-value { font-size: 2rem; font-weight: 800; margin-top: 0.5rem; }

    /* Table Styling */
    .table-container {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
    }
    .table-header { padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); background: #f8fafc; }
    
    table { width: 100%; border-collapse: collapse; }
    th { padding: 1rem 2rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; text-align: left; }
    td { padding: 1.25rem 2rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }

    /* Badges */
    .badge {
        padding: 0.35rem 0.85rem;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .bg-pending { background: #fef3c7; color: #92400e; }
    .bg-dipinjam { background: #dbeafe; color: #1e40af; }
    .bg-dikembalikan { background: #d1fae5; color: #065f46; }
    .bg-ditolak { background: #fee2e2; color: #991b1b; }
    .bg-purple { background: #f5f3ff; color: #7c3aed; }
</style>

<div class="detail-wrapper">
    <a href="{{ route('petugas.borrowers.index') }}" class="back-link">
        &larr; Kembali ke Daftar Peminjam
    </a>
    
    <div class="header-content">
        <h1 class="page-title">{{ $borrower->name }}</h1>
        <p class="page-subtitle">Manajemen profil anggota dan log aktivitas peminjaman buku.</p>
    </div>

    <div class="detail-grid">
        <div class="glass-card">
            <h2 class="card-title">👤 Informasi Profil</h2>
            <div class="info-list">
                <div class="info-item">
                    <span class="label">Nama Lengkap</span>
                    <span class="value">{{ $borrower->name }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Alamat Email</span>
                    <span class="value">{{ $borrower->email }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Peran (Role)</span>
                    <span class="value">
                        <span class="badge bg-purple">{{ ucfirst($borrower->role) }}</span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="label">Bergabung Sejak</span>
                    <span class="value">{{ $borrower->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Pembaruan Terakhir</span>
                    <span class="value">{{ $borrower->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <h2 class="card-title">📈 Ringkasan</h2>
            <div class="stat-card stat-blue">
                <div class="stat-label">Sedang Dipinjam</div>
                <div class="stat-value">{{ $activeLoans }}</div>
            </div>
            <div class="stat-card stat-green">
                <div class="stat-label">Total Peminjaman</div>
                <div class="stat-value">{{ $totalLoans }}</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h2 style="margin: 0; font-size: 1.25rem; font-weight: 700;">🕒 Riwayat Peminjaman Buku</h2>
        </div>
        
        <div style="overflow-x: auto;">
            @if ($borrowingHistory->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Waktu Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                            <th>Waktu Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borrowingHistory as $record)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b;">{{ $record->buku->nama_buku ?? 'N/A' }}</div>
                                </td>
                                <td>{{ $record->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $record->tanggal_kembali->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $statusClass = 'bg-' . $record->status;
                                        $statusLabel = [
                                            'pending' => 'Menunggu',
                                            'dipinjam' => 'Dipinjam',
                                            'dikembalikan' => 'Kembali',
                                            'ditolak' => 'Ditolak'
                                        ][$record->status] ?? $record->status;
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <span style="color: var(--text-muted);">
                                        {{ $record->tanggal_pengembalian ? $record->tanggal_pengembalian->format('d/m/Y H:i') : '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding: 1.5rem 2rem; background: #f8fafc; border-top: 1px solid var(--border);">
                    {{ $borrowingHistory->links() }}
                </div>
            @else
                <div style="padding: 4rem; text-align: center; color: var(--text-muted);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📖</div>
                    <p>Anggota ini belum pernah melakukan peminjaman buku.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
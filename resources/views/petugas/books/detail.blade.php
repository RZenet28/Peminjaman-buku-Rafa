@extends('layouts.petugas')

@section('content')
<style>
    :root {
        --primary: #10b981;
        --secondary: #3b82f6;
        --warning: #f59e0b;
        --danger: #ef4444;
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

    /* Header & Navigation */
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

    .page-title { font-size: 2.25rem; font-weight: 800; margin: 0; color: #0f172a; }
    .page-subtitle { color: var(--text-muted); margin-top: 0.5rem; margin-bottom: 2.5rem; }

    /* Grid Layout */
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
    }

    /* Cards */
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

    /* Info List */
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-item:last-child { border-bottom: none; }
    .label { font-weight: 600; color: var(--text-muted); }
    .value { font-weight: 700; color: var(--text-main); text-align: right; }

    /* Stock Boxes */
    .stock-box {
        padding: 1.25rem;
        border-radius: 12px;
        text-align: center;
        margin-bottom: 1rem;
    }
    .sb-total { background: #f1f5f9; }
    .sb-available { background: #ecfdf5; color: #065f46; border: 1px solid #d1fae5; }
    .sb-borrowed { background: #fff7ed; color: #9a3412; border: 1px solid #ffedd5; }

    .sb-label { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; opacity: 0.8; }
    .sb-value { font-size: 1.75rem; font-weight: 800; margin-top: 0.25rem; }

    /* Table Design */
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

    /* Status Badges */
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
</style>

<div class="detail-wrapper">
    <a href="{{ route('petugas.books.index') }}" class="back-link">
        &larr; Kembali ke Daftar Buku
    </a>
    
    <h1 class="page-title">{{ $book->nama_buku }}</h1>
    <p class="page-subtitle">Detail spesifikasi buku dan riwayat aktivitas peminjaman.</p>

    <div class="detail-grid">
        <div class="glass-card">
            <h2 class="card-title">📖 Informasi Buku</h2>
            <div class="info-list">
                <div class="info-item">
                    <span class="label">Nama Pengarang</span>
                    <span class="value">{{ $book->pengarang ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Penerbit</span>
                    <span class="value">{{ $book->penerbit ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Tahun Terbit</span>
                    <span class="value">{{ $book->tahun ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Nomor ISBN</span>
                    <span class="value"><code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">{{ $book->isbn ?? '-' }}</code></span>
                </div>
                <div class="info-item">
                    <span class="label">Kategori</span>
                    <span class="value">
                        <span class="badge" style="background: #eff6ff; color: #2563eb;">{{ $book->category->name ?? '-' }}</span>
                    </span>
                </div>
                @if ($book->deskripsi)
                <div style="margin-top: 1.5rem;">
                    <span class="label">Deskripsi</span>
                    <p style="margin-top: 0.75rem; line-height: 1.6; color: var(--text-main); font-size: 0.95rem;">
                        {{ $book->deskripsi }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <div class="glass-card">
            <h2 class="card-title">📊 Status Stok</h2>
            <div class="stock-box sb-total">
                <div class="sb-label">Total Inventaris</div>
                <div class="sb-value">{{ $book->stock ?? 0 }}</div>
            </div>
            <div class="stock-box sb-available">
                <div class="sb-label">Stok Tersedia</div>
                <div class="sb-value">{{ $book->stock ?? 0 }}</div>
            </div>
            <div class="stock-box sb-borrowed">
                <div class="sb-label">Sedang Dipinjam</div>
                <div class="sb-value">0</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h2 style="margin: 0; font-size: 1.25rem; font-weight: 700;">🕒 Riwayat Peminjaman</h2>
        </div>
        
        <div style="overflow-x: auto;">
            @if ($borrowingHistory->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Peminjam</th>
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
                                    <div style="font-weight: 700;">{{ $record->user->name }}</div>
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
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                    <p>Belum ada riwayat peminjaman untuk buku ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
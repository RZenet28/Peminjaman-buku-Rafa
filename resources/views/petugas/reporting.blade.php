@extends('layouts.petugas')

@section('content')
<style>
    :root {
        --bg-main: #f4f7f6;
        --card-bg: #ffffff;
        --primary: #10b981;
        --primary-dark: #059669;
        --secondary: #3b82f6;
        --danger: #ef4444;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --border: #e5e7eb;
    }

    .report-wrapper {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
        background-color: var(--bg-main);
        min-height: 100vh;
    }

    .header-content { margin-bottom: 2.5rem; }
    .header-content h1 { font-size: 2rem; color: var(--text-dark); margin: 0; font-weight: 800; }
    .header-content p { color: var(--text-light); margin-top: 0.5rem; }

    /* Card System */
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-item {
        padding: 1.5rem;
        border-radius: 16px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .stat-total { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-done { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .stat-late { background: linear-gradient(135deg, #ef4444, #dc2626); }
    
    .stat-label { font-size: 0.875rem; opacity: 0.9; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 2.5rem; font-weight: 800; margin-top: 0.5rem; }

    /* Forms */
    .filter-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: flex-end;
    }
    .input-group { flex: 1; min-width: 200px; }
    .input-group label { display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem; text-transform: uppercase; }
    
    .custom-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #f9fafb;
        transition: all 0.3s;
    }
    .custom-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

    /* Buttons */
    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: transform 0.2s, background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-action:active { transform: scale(0.98); }
    .btn-green { background: var(--primary); color: white; }
    .btn-blue { background: var(--secondary); color: white; }

    /* Tables */
    .table-container { overflow-x: auto; }
    .modern-table { width: 100%; border-collapse: collapse; text-align: left; }
    .modern-table th { padding: 1rem; background: #f8fafc; color: var(--text-light); font-size: 0.75rem; text-transform: uppercase; border-bottom: 1px solid var(--border); }
    .modern-table td { padding: 1.25rem 1rem; border-bottom: 1px solid var(--border); font-size: 0.9rem; color: var(--text-dark); }
    .modern-table tr:last-child td { border-bottom: none; }
    .rank-badge { background: #f3f4f6; padding: 0.25rem 0.6rem; border-radius: 6px; font-weight: 800; color: var(--text-light); }
    
    .section-title { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
</style>

<div class="report-wrapper">
    <div class="header-content">
        <h1>📊 Laporan Strategis</h1>
        <p>Analisis performa peminjaman dan distribusi buku perpustakaan.</p>
    </div>

    <div class="glass-card">
        <form action="{{ route('petugas.reporting') }}" method="GET" class="filter-flex">
            <div class="input-group">
                <label>Periode Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="custom-input">
            </div>
            <div class="input-group">
                <label>Periode Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="custom-input">
            </div>
            <button type="submit" class="btn-action btn-green">
                Perbarui Data
            </button>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-item stat-total">
            <div class="stat-label">Total Peminjaman</div>
            <div class="stat-value">{{ $totalBorrowings }}</div>
        </div>
        <div class="stat-item stat-done">
            <div class="stat-label">Selesai Kembali</div>
            <div class="stat-value">{{ $completedBorrowings }}</div>
        </div>
        <div class="stat-item stat-late">
            <div class="stat-label">Terlambat</div>
            <div class="stat-value">{{ $lateBorrowings }}</div>
        </div>
    </div>

    <div class="glass-card">
        <h2 class="section-title">📥 Ekspor Dokumen</h2>
        <form action="{{ route('petugas.export_report') }}" method="POST" class="filter-flex">
            @csrf
            <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">

            <div class="input-group" style="max-width: 250px;">
                <label>Pilih Format File</label>
                <select name="format" class="custom-input">
                    <option value="csv">Microsoft Excel (CSV)</option>
                    <option value="pdf">Dokumen PDF (HTML)</option>
                </select>
            </div>
            <button type="submit" class="btn-action btn-blue">
                Unduh Laporan Sekarang
            </button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 2rem;">
        
        <div class="glass-card" style="padding: 0;">
            <div style="padding: 1.5rem;">
                <h2 class="section-title" style="margin-bottom: 0;">🔥 Buku Terpopuler</h2>
            </div>
            <div class="table-container">
                @if ($topBooks->count() > 0)
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Rank</th>
                                <th>Judul Buku</th>
                                <th style="text-align: center;">Pinjaman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topBooks as $idx => $book)
                                <tr>
                                    <td><span class="rank-badge">#{{ $idx + 1 }}</span></td>
                                    <td>
                                        <div style="font-weight: 700;">{{ $book->nama_buku }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-light);">{{ $book->pengarang ?? '-' }}</div>
                                    </td>
                                    <td style="text-align: center; font-weight: 800; color: var(--primary);">{{ $book->peminjaman_count ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding: 2rem; text-align: center; color: var(--text-light);">Belum ada data</div>
                @endif
            </div>
        </div>

        <div class="glass-card" style="padding: 0;">
            <div style="padding: 1.5rem;">
                <h2 class="section-title" style="margin-bottom: 0;">🏆 Peminjam Teraktif</h2>
            </div>
            <div class="table-container">
                @if ($topBorrowers->count() > 0)
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Rank</th>
                                <th>Nama Lengkap</th>
                                <th style="text-align: center;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topBorrowers as $idx => $borrower)
                                <tr>
                                    <td><span class="rank-badge">#{{ $idx + 1 }}</span></td>
                                    <td>
                                        <div style="font-weight: 700;">{{ $borrower->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-light);">{{ $borrower->email }}</div>
                                    </td>
                                    <td style="text-align: center; font-weight: 800; color: var(--secondary);">{{ $borrower->peminjaman_count ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding: 2rem; text-align: center; color: var(--text-light);">Belum ada data</div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
@extends('layouts.petugas')

@section('content')
<style>
    :root {
        --primary: #10b981;
        --primary-soft: rgba(16, 185, 129, 0.1);
        --bg-page: #f8fafc;
        --white: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .borrowers-wrapper {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        color: var(--text-main);
    }

    /* Header */
    .header-section { margin-bottom: 2rem; }
    .header-section h1 { font-size: 1.875rem; font-weight: 800; letter-spacing: -0.025em; margin-bottom: 0.5rem; }
    .header-section p { color: var(--text-muted); font-size: 1rem; }

    /* Search Card */
    .glass-card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .search-flex {
        display: flex;
        align-items: flex-end;
        gap: 1rem;
    }

    .input-group { flex: 1; }
    .input-group label { 
        display: block; 
        font-size: 0.75rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background-color: #f1f5f9;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        background-color: var(--white);
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    /* Table Design */
    .table-container {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { padding: 1rem 1.5rem; background: #f8fafc; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; border-bottom: 1px solid var(--border-color); }
    td { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

    /* Avatar Style */
    .user-info { display: flex; align-items: center; gap: 1rem; }
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--primary-soft);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .user-details .name { display: block; font-weight: 700; color: var(--text-main); }
    .user-details .email { font-size: 0.813rem; color: var(--text-muted); }

    /* Badges */
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }
    .badge-purple { background: #f5f3ff; color: #7c3aed; }

    /* Buttons */
    .btn {
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-gray { background: #f1f5f9; color: var(--text-muted); }
    .btn-outline { border: 1px solid var(--border-color); color: var(--text-main); background: white; }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

</style>

<div class="borrowers-wrapper">
    <div class="header-section">
        <h1>Data Peminjam</h1>
        <p>Kelola data anggota dan pantau riwayat peminjaman mereka.</p>
    </div>

    @if ($message = Session::get('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 5px solid var(--primary);">
            <b>Sukses!</b> {{ $message }}
        </div>
    @endif

    <div class="glass-card">
        <form action="{{ route('petugas.borrowers.index') }}" method="GET" class="search-flex">
            <div class="input-group">
                <label>Cari Peminjam</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="form-input">
            </div>
            <button type="submit" class="btn btn-primary">🔍 Cari</button>
            <a href="{{ route('petugas.borrowers.index') }}" class="btn btn-gray">Reset</a>
        </form>
    </div>

    <div class="table-container">
        @if ($borrowers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Status Peran</th>
                        <th style="text-align: center;">Tgl Bergabung</th>
                        <th style="text-align: center;">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowers as $borrower)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($borrower->name, 0, 2)) }}
                                    </div>
                                    <div class="user-details">
                                        <span class="name">{{ $borrower->name }}</span>
                                        <span class="email">{{ $borrower->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-purple">
                                    {{ ucfirst($borrower->role) }}
                                </span>
                            </td>
                            <td style="text-align: center; color: var(--text-muted); font-size: 0.875rem;">
                                {{ $borrower->created_at->format('d M Y') }}
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('petugas.borrowers.detail', $borrower->id) }}" class="btn btn-outline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: center;">
                {{ $borrowers->links() }}
            </div>
        @else
            <div style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                <h3>Peminjam tidak ditemukan</h3>
                <p>Coba gunakan kata kunci pencarian yang berbeda.</p>
            </div>
        @endif
    </div>
</div>
@endsection
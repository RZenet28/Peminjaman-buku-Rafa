@extends('layouts.petugas')

@section('content')
<style>
    :root {
        --primary: #10b981;
        --primary-dark: #059669;
        --bg-body: #f8fafc;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --white: #ffffff;
        --border-color: #e2e8f0;
        --danger: #ef4444;
        --info: #3b82f6;
    }

    .monitor-container {
        font-family: 'Inter', sans-serif;
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        color: var(--text-main);
    }

    /* Header */
    .header-section {
        margin-bottom: 2rem;
    }
    .header-section h1 {
        font-size: 1.875rem;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
    }
    .header-section p {
        color: var(--text-muted);
        margin-top: 0.5rem;
    }

    /* Alert */
    .alert-success {
        background-color: #ecfdf5;
        border-left: 4px solid var(--primary);
        color: #065f46;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    /* Cards & Filters */
    .card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        align-items: flex-end;
    }

    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: #f1f5f9;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        background-color: var(--white);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    th {
        background-color: #f8fafc;
        padding: 1rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-color);
    }

    td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-danger { background: #fef2f2; color: var(--danger); border: 1px solid #fee2e2; }
    .badge-info { background: #eff6ff; color: var(--info); border: 1px solid #dbeafe; }

    /* Buttons */
    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: var(--primary-dark); }
    .btn-secondary { background: #64748b; color: white; }
    .btn-outline { background: transparent; border: 1px solid var(--border-color); color: var(--text-muted); }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .modal-active { display: flex; }
</style>

<div class="monitor-container">
    <div class="header-section">
        <h1>Monitor Pengembalian Buku</h1>
        <p>Pantau dan catat pengembalian buku dari peminjam secara efisien.</p>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert-success">
            {{ $message }}
        </div>
    @endif

    <div class="card">
        <form action="{{ route('petugas.monitor_returns') }}" method="GET">
            <div class="filter-grid">
                <div class="form-group">
                    <label>Filter Status</label>
                    <select name="filter" class="form-control">
                        <option value="">Semua</option>
                        <option value="overdue" @if(request('filter') == 'overdue') selected @endif>⚠️ Terlambat</option>
                        <option value="today" @if(request('filter') == 'today') selected @endif>📅 Hari Ini</option>
                        <option value="soon" @if(request('filter') == 'soon') selected @endif>⏳ 3 Hari Ke Depan</option>
                    </select>
                </div>
                <div class="form-group" style="flex-grow: 2;">
                    <label>Cari Nama / Buku</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="form-control">
                </div>
                <div class="form-group" style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('petugas.monitor_returns') }}" class="btn btn-outline">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            @if ($returns->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tenggat Waktu</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($returns as $return)
                            <tr>
                                <td><strong>{{ $return->user->name }}</strong></td>
                                <td>{{ $return->buku->nama_buku ?? 'N/A' }}</td>
                                <td>
                                    @if ($return->tanggal_kembali < now())
                                        <span class="badge badge-danger">Terlambat ({{ $return->tanggal_kembali->format('d/m/y') }})</span>
                                    @else
                                        <span class="badge badge-info">{{ $return->tanggal_kembali->format('d M Y') }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <button onclick="openReturnModal({{ $return->id }})" class="btn btn-outline" style="padding: 0.4rem 0.8rem;">Catat Kembali</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                    <p>Tidak ada pengembalian buku yang ditemukan.</p>
                </div>
            @endif
        </div>
    </div>
    
    <div style="margin-top: 1rem;">
        {{ $returns->links() }}
    </div>
</div>

<div id="returnModal" class="modal-overlay">
    <div class="modal-content">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem;">Catat Pengembalian</h3>
        <form id="returnForm" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label>Kondisi Buku</label>
                <select name="condition" class="form-control" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="baik">Sangat Baik</option>
                    <option value="rusak">Rusak / Hilang</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>Catatan Tambahan</label>
                <textarea name="notes" class="form-control" style="height: 100px; resize: none;"></textarea>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan Data</button>
                <button type="button" onclick="closeReturnModal()" class="btn btn-outline" style="flex: 1;">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReturnModal(id) {
        const modal = document.getElementById('returnModal');
        const form = document.getElementById('returnForm');
        form.action = '/petugas/record-return/' + id; // Sesuaikan dengan route Anda
        modal.classList.add('modal-active');
    }

    function closeReturnModal() {
        document.getElementById('returnModal').classList.remove('modal-active');
    }

    // Tutup modal jika klik di luar area putih
    window.onclick = function(event) {
        const modal = document.getElementById('returnModal');
        if (event.target == modal) {
            closeReturnModal();
        }
    }
</script>
@endsection
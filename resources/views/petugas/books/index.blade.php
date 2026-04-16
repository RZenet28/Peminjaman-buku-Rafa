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
        --accent-blue: #3b82f6;
    }

    .books-container {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        color: var(--text-main);
    }

    /* Header */
    .header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .header-flex h1 { font-size: 1.875rem; font-weight: 800; margin: 0; letter-spacing: -0.025em; }
    .header-flex p { color: var(--text-muted); margin-top: 0.25rem; }

    /* Cards */
    .glass-card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    /* Filter Grid */
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
        align-items: flex-end;
    }

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
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        background-color: var(--white);
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    /* Table Design */
    .table-wrapper { overflow-x: auto; border-radius: 16px; border: 1px solid var(--border-color); background: white; }
    table { width: 100%; border-collapse: collapse; text-align: left; min-width: 800px; }
    th { padding: 1rem; background: #f8fafc; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border-color); }
    td { padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    
    .book-title { font-weight: 700; color: var(--text-main); display: block; margin-bottom: 0.25rem; }
    .book-meta { font-size: 0.75rem; color: var(--text-muted); display: flex; gap: 0.5rem; }

    /* Status Badges */
    .badge { padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
    .badge-category { background: #eff6ff; color: var(--accent-blue); }
    
    .stock-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .dot { width: 8px; height: 8px; border-radius: 50%; }
    .dot-green { background: var(--primary); box-shadow: 0 0 8px var(--primary); }
    .dot-red { background: #ef4444; box-shadow: 0 0 8px #ef4444; }

    /* Buttons */
    .btn {
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #0d9488; transform: translateY(-1px); }
    .btn-gray { background: #f1f5f9; color: var(--text-muted); }
    .btn-outline { border: 1px solid var(--border-color); background: white; color: var(--text-main); }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

</style>

<div class="books-container">
    <div class="header-flex">
        <div>
            <h1>Data Buku</h1>
            <p>Kelola dan pantau koleksi buku perpustakaan Anda.</p>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 5px solid var(--primary);">
            <b>Sukses!</b> {{ $message }}
        </div>
    @endif

    <div class="glass-card">
        <form action="{{ route('petugas.books.index') }}" method="GET" class="filter-grid">
            <div class="input-group">
                <label>Pilih Kategori</label>
                <select name="kategori" class="form-input">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @if(request('kategori') == $cat) selected @endif>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group" style="flex-grow: 2;">
                <label>Cari Judul, Penulis, atau ISBN</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="form-input">
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">🔍 Cari</button>
                <a href="{{ route('petugas.books.index') }}" class="btn btn-gray">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-wrapper">
        @if ($books->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Informasi Buku</th>
                        <th>ISBN</th>
                        <th style="text-align: center;">Total Stok</th>
                        <th style="text-align: center;">Tersedia</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td>
                                <span class="book-title">{{ $book->nama_buku }}</span>
                                <div class="book-meta">
                                    <span style="color: var(--primary); font-weight: 600;">{{ $book->pengarang ?? '-' }}</span>
                                    <span>•</span>
                                    <span class="badge badge-category">{{ $book->category->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td><code style="font-size: 0.8rem; background: #f1f5f9; padding: 2px 5px; border-radius: 4px;">{{ $book->isbn ?? '-' }}</code></td>
                            <td style="text-align: center; font-weight: 600;">{{ $book->stock ?? 0 }}</td>
                            <td style="text-align: center;">
                                <div class="stock-indicator" style="justify-content: center;">
                                    @if (($book->stock ?? 0) > 0)
                                        <span class="dot dot-green"></span>
                                        <span style="color: var(--primary);">{{ $book->stock }} unit</span>
                                    @else
                                        <span class="dot dot-red"></span>
                                        <span style="color: #ef4444;">Habis</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('petugas.books.detail', $book->id) }}" class="btn btn-outline">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
                <h3>Buku tidak ditemukan</h3>
                <p>Coba kata kunci lain atau periksa filter kategori.</p>
            </div>
        @endif
    </div>

    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $books->links() }}
    </div>
</div>
@endsection
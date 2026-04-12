@extends('layouts.peminjam')

@section('title', 'Daftar Koleksi Buku')

@section('content')
<style>
    .book-card {
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
    }
    .book-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .book-img-container {
        position: relative;
        overflow: hidden;
        aspect-ratio: 2/3;
    }
    .book-img-container img {
        transition: transform 0.5s ease;
    }
    .book-card:hover .book-img-container img {
        transform: scale(1.1);
    }
    .category-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 6px 12px;
        border-radius: 8px;
        backdrop-filter: blur(8px);
        background: rgba(102, 126, 234, 0.85);
        color: white;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
    }
    .btn-pinjam {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-pinjam:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }
    .stock-info {
        font-size: 13px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .search-section {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
</style>

<div class="container-fluid py-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">E-Katalog Perpustakaan</h2>
            <p class="text-muted">Temukan pengetahuan dalam genggamanmu.</p>
        </div>
        <div class="col-md-6">
            <div class="search-section d-flex align-items-center">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Cari judul buku atau kategori...">
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($books as $buku)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card book-card border-0 shadow-sm h-100">
                    <div class="book-img-container">
                        <span class="category-badge">{{ $buku->category->name ?? 'Umum' }}</span>
                        @if($buku->gambar)
                            <img src="{{ asset('storage/' . $buku->gambar) }}" class="w-100 h-100 object-fit-cover" alt="{{ $buku->nama_buku }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                <i class="bi bi-book text-muted opacity-25" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="fw-bold text-dark mb-2 text-truncate-2" style="height: 3rem; line-height: 1.5rem;">
                            {{ $buku->nama_buku }}
                        </h5>
                        
                        <div class="d-flex align-items-center mb-4 mt-auto">
                            <div class="stock-info">
                                <i class="bi bi-box-seam"></i>
                                <span>Stok: <strong>{{ $buku->stock }}</strong></span>
                            </div>
                        </div>

                        <a href="#" class="btn btn-pinjam text-white w-100 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i>Pinjam Buku
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-journal-x text-muted opacity-25" style="font-size: 5rem;"></i>
                </div>
                <h4 class="text-muted">Maaf, koleksi buku belum tersedia.</h4>
                <p class="text-muted">Silakan hubungi pustakawan atau admin.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="container-fluid p-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kelola Kategori</h2>
            <p class="text-muted mb-0">Manajemen kategori buku perpustakaan</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
        </button>
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama kategori..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-2"></i>Cari
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 me-3" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                            <i class="bi bi-tag-fill text-white" style="font-size: 28px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total Kategori</p>
                            <h3 class="fw-bold mb-0">{{ $categories->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body">
                        <!-- Category Icon -->
                        <div class="text-center mb-3">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, {{ $loop->index % 5 == 0 ? '#6366f1, #4f46e5' : ($loop->index % 5 == 1 ? '#10b981, #059669' : ($loop->index % 5 == 2 ? '#f59e0b, #d97706' : ($loop->index % 5 == 3 ? '#ec4899, #db2777' : '#06b6d4, #0891b2'))) }});">
                                <i class="bi bi-tag-fill text-white" style="font-size: 36px;"></i>
                            </div>
                        </div>

                        <!-- Category Name -->
                        <h5 class="text-center fw-bold mb-3">{{ $category->name }}</h5>

                        <!-- Books Count -->
                        <div class="text-center mb-3">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-book me-1"></i>
                                {{ $category->books_count ?? 0 }} Buku (Stock: {{ $category->books_sum_stock ?? 0 }})
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary flex-fill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $category->id }}"
                                    title="Edit">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="deleteCategory({{ $category->id }})"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <!-- Hidden Delete Form -->
                        <form id="delete-form-{{ $category->id }}" 
                              action="{{ route('admin.categories.destroy', $category->id) }}" 
                              method="POST" 
                              class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="modal-header border-0 pb-0">
                                <div>
                                    <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                    <p class="text-muted small mb-0">Update nama kategori</p>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-2 text-primary"></i>Nama Kategori
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control form-control-lg" 
                                       value="{{ $category->name }}" 
                                       placeholder="Masukkan nama kategori"
                                       required>
                            </div>

                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Update Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-tags fs-1 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">Belum ada kategori</h5>
                        <p class="text-muted mb-3">Tambahkan kategori pertama Anda</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $categories->withQueryString()->links() }}
        </div>
    @endif

</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                        <p class="text-muted small mb-0">Isi form dibawah untuk menambahkan kategori</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-tag me-2 text-primary"></i>Nama Kategori
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-lg" 
                           placeholder="Contoh: Fiksi, Non-Fiksi, Pelajaran"
                           required
                           autofocus>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Masukkan nama kategori yang jelas dan mudah dipahami
                    </small>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .hover-lift {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
    }

    /* Category card animation */
    .card {
        animation: fadeInUp 0.4s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@endsection

@push('scripts')
<script>
    function deleteCategory(categoryId) {
        if(confirm('Apakah Anda yakin ingin menghapus kategori ini?\n\nSemua buku dalam kategori ini akan kehilangan kategorisasi.')) {
            document.getElementById('delete-form-' + categoryId).submit();
        }
    }
</script>
@endpush
@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid p-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Manajemen User</h2>
            <p class="text-muted mb-0">Kelola akses akun petugas dan peminjam</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle me-2"></i>Tambah User
        </button>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 me-3" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                            <i class="bi bi-people-fill text-white" style="font-size: 28px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total User</p>
                            <h3 class="fw-bold mb-0">{{ $users->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 me-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="bi bi-person-badge-fill text-white" style="font-size: 28px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Petugas</p>
                            <h3 class="fw-bold mb-0">{{ $users->where('role', 'petugas')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 me-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                            <i class="bi bi-mortarboard-fill text-white" style="font-size: 28px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Peminjam</p>
                            <h3 class="fw-bold mb-0">{{ $users->where('role', 'peminjam')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <ul class="nav nav-pills gap-2" id="roleFilter">
                <li class="nav-item">
                    <button class="nav-link active" onclick="filterUsers('all')">
                        <i class="bi bi-people me-2"></i>Semua User
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" onclick="filterUsers('petugas')">
                        <i class="bi bi-person-badge me-2"></i>Petugas
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" onclick="filterUsers('peminjam')">
                        <i class="bi bi-person me-2"></i>Peminjam
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 border-0">User</th>
                            <th class="py-3 border-0">Role</th>
                            <th class="py-3 border-0">Data Siswa</th>
                            <th class="px-4 py-3 border-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @forelse($users as $user)
                            <tr class="user-row" data-role="{{ $user->role }}">
                                <td class="px-4 py-3 align-middle">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                             style="width: 45px; height: 45px; background: {{ $user->role == 'petugas' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #8b5cf6, #7c3aed)' }};">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 align-middle">
                                    @if($user->role == 'petugas')
                                        <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46;">
                                            <i class="bi bi-person-badge me-1"></i>Petugas
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #e9d5ff, #d8b4fe); color: #6b21a8;">
                                            <i class="bi bi-person me-1"></i>Peminjam
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 align-middle">
                                    @if($user->role == 'peminjam' && $user->siswa)
                                        <div class="small">
                                            <div class="fw-semibold text-dark">{{ $user->siswa->nama_lengkap }}</div>
                                            <div class="text-muted">
                                                <i class="bi bi-mortarboard me-1"></i>
                                                {{ $user->siswa->kelas }} - {{ $user->siswa->jurusan }}
                                            </div>
                                            @if($user->siswa->nisn)
                                                <div class="text-muted">
                                                    <i class="bi bi-card-text me-1"></i>
                                                    NISN: {{ $user->siswa->nisn }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 align-middle text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Edit User">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus User">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data user</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold">Tambah User Baru</h5>
                        <p class="text-muted small mb-0">Isi form dibawah untuk menambahkan user</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person me-2 text-primary"></i>Username
                        </label>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               placeholder="Contoh: budi_santoso"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-envelope me-2 text-primary"></i>Email
                        </label>
                        <input type="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="email@sekolah.sch.id"
                               required>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Password akan dibuat otomatis dan dikirim ke email
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-shield-check me-2 text-primary"></i>Role Pengguna
                        </label>
                        <select name="role" id="create_role" class="form-select">
                            <option value="petugas">Petugas Perpustakaan</option>
                            <option value="peminjam">Peminjam (Siswa)</option>
                        </select>
                    </div>

                    <!-- Siswa Fields -->
                    <div id="createSiswaFields" style="display:none;">
                        <div class="alert alert-info border-0 bg-info bg-opacity-10">
                            <i class="bi bi-mortarboard me-2"></i>
                            <strong>Data Siswa</strong> - Lengkapi informasi dibawah
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap Siswa</label>
                            <input type="text" 
                                   name="nama_lengkap" 
                                   class="form-control" 
                                   placeholder="Nama lengkap siswa">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kelas</label>
                                <select name="kelas" class="form-select">
                                    <option value="">Pilih Kelas</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jurusan</label>
                                <select name="jurusan" class="form-select">
                                    <option value="">Pilih Jurusan</option>
                                    <option value="PPLG">PPLG</option>
                                    <option value="BCF">BCF</option>
                                    <option value="TO">TO</option>
                                    <option value="TPFL">TPFL</option>
                                    <option value="ANIMASI">ANIMASI</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">NISN (Opsional)</label>
                            <input type="text" 
                                   name="nisn" 
                                   class="form-control" 
                                   placeholder="Nomor Induk Siswa Nasional">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Table hover effect */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.001);
        transition: all 0.2s ease;
    }

    /* Nav pills custom */
    .nav-pills .nav-link {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
    }

    .nav-pills .nav-link:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    /* Button group hover */
    .btn-group .btn:hover {
        transform: translateY(-2px);
    }
</style>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const createRole = document.getElementById('create_role');
        const createSiswaFields = document.getElementById('createSiswaFields');

        createRole.addEventListener('change', function () {
            if(this.value === 'peminjam') {
                createSiswaFields.style.display = 'block';
                // Make siswa fields required
                createSiswaFields.querySelectorAll('input, select').forEach(el => {
                    if(el.name !== 'nisn') {
                        el.setAttribute('required', 'required');
                    }
                });
            } else {
                createSiswaFields.style.display = 'none';
                // Remove required from siswa fields
                createSiswaFields.querySelectorAll('input, select').forEach(el => {
                    el.removeAttribute('required');
                });
            }
        });
    });

    // Filter function
    function filterUsers(role) {
        const rows = document.querySelectorAll('.user-row');
        const navLinks = document.querySelectorAll('.nav-pills .nav-link');
        
        // Update active tab
        navLinks.forEach(link => link.classList.remove('active'));
        event.target.classList.add('active');
        
        // Filter rows
        rows.forEach(row => {
            if(role === 'all' || row.dataset.role === role) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
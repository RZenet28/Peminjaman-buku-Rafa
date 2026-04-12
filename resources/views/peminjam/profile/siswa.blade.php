@extends('layouts.peminjam')

@section('title', 'Profile Siswa')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --primary-hover: #4338ca;
        --bg-body: #f8fafc;
        --card-bg: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --input-focus: #c7d2fe;
        --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
    }

    .profile-container {
        max-width: 800px;
        margin: auto;
    }

    .card-modern {
        background: var(--card-bg);
        border: none;
        border-radius: 20px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
    }

    .form-section-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .form-section-title::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--border-color);
        margin-left: 15px;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-main);
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid var(--border-color);
        background-color: #fdfdfd;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        box-shadow: 0 0 0 4px var(--input-focus);
        border-color: var(--primary);
        background-color: #fff;
    }

    .btn-update {
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .btn-update:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
    }

    .alert-modern {
        border: none;
        border-radius: 14px;
        padding: 15px 20px;
        font-weight: 500;
    }

    hr {
        border-top: 1px solid var(--border-color);
        margin: 30px 0;
        opacity: 0.5;
    }
</style>

<div class="container py-5">
    <div class="profile-container">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0" style="color: var(--text-main);">Profile Siswa</h3>
            <span class="badge bg-white text-primary border px-3 py-2 rounded-pill shadow-sm">
                ID: #{{ $user->id }}
            </span>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-modern shadow-sm mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="card card-modern">
            <div class="card-header-gradient">
                <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-person-fill text-white" style="font-size: 40px;"></i>
                </div>
                <h4 class="mb-0 fw-bold">{{ $user->name }}</h4>
                <p class="mb-0 opacity-75 small">{{ $user->email }}</p>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('peminjam.profile.siswa.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section-title">Data Akun</div>
                    
                    <div class="row g-4 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ $user->name }}" placeholder="Masukkan username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ $user->email }}" placeholder="nama@email.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Password Baru <span class="text-muted fw-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control border-start-0" 
                                       placeholder="••••••••" style="border-radius: 0 12px 12px 0;">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-section-title">Informasi Akademik</div>

                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap Siswa</label>
                        <input type="text" name="nama_lengkap" class="form-control"
                               value="{{ $user->siswa->nama_lengkap ?? '' }}" placeholder="Nama sesuai ijazah" required>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" class="form-control"
                                   value="{{ $user->siswa->kelas ?? '' }}" placeholder="Contoh: XII-RPL-1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control"
                                   value="{{ $user->siswa->jurusan ?? '' }}" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                            <input type="text" name="nisn" class="form-control"
                                   value="{{ $user->siswa->nisn ?? '' }}" placeholder="Masukkan 10 digit NISN">
                        </div>
                    </div>

                    <div class="text-end mt-5">
                        <button class="btn btn-update">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">Terakhir diperbarui: <span class="fw-semibold">{{ $user->updated_at->format('d M Y, H:i') }}</span></p>
        </div>

    </div>
</div>
@endsection
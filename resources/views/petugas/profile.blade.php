@extends('layouts.petugas')

@section('content')
<style>
    :root {
        --primary: #10b981;
        --primary-hover: #059669;
        --primary-soft: rgba(16, 185, 129, 0.1);
        --blue: #3b82f6;
        --bg-page: #f8fafc;
        --white: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .profile-wrapper {
        font-family: 'Inter', sans-serif;
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        color: var(--text-main);
    }

    /* Header */
    .header-section { margin-bottom: 2.5rem; }
    .header-section h1 { font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; }
    .header-section p { color: var(--text-muted); margin-top: 0.5rem; }

    /* Layout Grid */
    .profile-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 768px) {
        .profile-grid { grid-template-columns: 1fr; }
    }

    /* Modern Card */
    .glass-card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #334155;
    }

    /* Profile Avatar Header */
    .avatar-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding-bottom: 2rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        background: var(--primary-soft);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 800;
    }

    .avatar-info h3 { margin: 0; font-size: 1.25rem; font-weight: 700; }
    .avatar-info p { margin: 0.25rem 0 0; color: var(--text-muted); font-size: 0.875rem; }

    /* Form Styling */
    .form-group { margin-bottom: 1.5rem; }
    .form-group label {
        display: block;
        font-size: 0.813rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        letter-spacing: 0.025em;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: #fcfcfd;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-soft);
        background: var(--white);
    }

    .form-input:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
        color: #94a3b8;
    }

    /* Buttons */
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary { background: var(--primary); color: white; width: 100%; }
    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-1px); }

    .btn-blue { background: #eff6ff; color: var(--blue); border: 1px solid #dbeafe; width: 100%; }
    .btn-blue:hover { background: var(--blue); color: white; }

    /* Security Tips */
    .tips-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }
    .tips-box h4 { font-size: 0.875rem; font-weight: 700; margin-bottom: 0.75rem; color: #475569; }
    .tips-list { padding-left: 1.25rem; margin: 0; color: var(--text-muted); font-size: 0.813rem; }
    .tips-list li { margin-bottom: 0.5rem; }

</style>

<div class="profile-wrapper">
    <div class="header-section">
        <h1>Profil Saya</h1>
        <p>Kelola pengaturan identitas dan keamanan akun petugas Anda.</p>
    </div>

    @if ($message = Session::get('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; border-left: 4px solid var(--primary); font-size: 0.9rem;">
            <b>Sukses!</b> {{ $message }}
        </div>
    @endif

    <div class="profile-grid">
        <div class="glass-card">
            <div class="avatar-header">
                <div class="avatar-circle">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="avatar-info">
                    <h3>{{ $user->name }}</h3>
                    <p>Petugas Perpustakaan</p>
                </div>
            </div>

            <h2 class="card-title">📝 Informasi Publik</h2>

            <form action="{{ route('petugas.update_profile') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    @error('name') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    @error('email') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" class="form-input" disabled>
                    </div>

                    <div class="form-group">
                        <label>Terdaftar Sejak</label>
                        <input type="text" value="{{ $user->created_at->format('d M Y') }}" class="form-input" disabled>
                    </div>
                </div>

                <div style="margin-top: 1rem; border-top: 1px solid #f1f5f9; padding-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        💾 Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <div class="glass-card">
            <h2 class="card-title">🔐 Keamanan</h2>
            
            <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                Lindungi akun Anda dengan memperbarui kata sandi secara rutin.
            </p>

            <a href="{{ route('petugas.change_password') }}" class="btn btn-blue">
                Ubah Password
            </a>

            <div class="tips-box">
                <h4>🛡️ Tips Keamanan</h4>
                <ul class="tips-list">
                    <li>Gunakan kombinasi simbol, angka, dan huruf kapital.</li>
                    <li>Jangan gunakan kata sandi yang sama dengan situs lain.</li>
                    <li>Segera ubah password jika merasa akun disusupi.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.petugas')

@section('content')
<style>
    :root {
        --primary: #10b981;
        --primary-hover: #059669;
        --primary-soft: rgba(16, 185, 129, 0.1);
        --bg-page: #f8fafc;
        --white: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .password-wrapper {
        font-family: 'Inter', sans-serif;
        max-width: 500px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }

    /* Header & Back Link */
    .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
    }
    .back-link:hover { transform: translateX(-5px); opacity: 0.8; }

    .header-section { text-align: center; margin-bottom: 2rem; }
    .header-section h1 { font-size: 1.875rem; font-weight: 800; color: #0f172a; margin: 0; }
    .header-section p { color: var(--text-muted); margin-top: 0.5rem; font-size: 0.95rem; }

    /* Modern Card */
    .glass-card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    /* Form Styling */
    .form-group { margin-bottom: 1.5rem; }
    .form-group label {
        display: block;
        font-size: 0.813rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.6rem;
        letter-spacing: 0.025em;
    }

    /* Password Container & Toggle */
    .password-field-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-input {
        width: 100%;
        padding: 0.8rem 1rem;
        padding-right: 3rem; /* Ruang untuk ikon mata */
        border: 1px solid var(--border-color);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.2s;
        background: #fcfcfd;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-soft);
        background: var(--white);
    }

    .toggle-password {
        position: absolute;
        right: 1rem;
        cursor: pointer;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        border-radius: 8px;
        transition: all 0.2s;
        user-select: none;
    }

    .toggle-password:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    /* Buttons */
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.85rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-1px); }

    .btn-light { background: #f1f5f9; color: #64748b; }
    .btn-light:hover { background: #e2e8f0; color: #475569; }

    .security-notice {
        margin-top: 2rem;
        padding: 1rem;
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-radius: 12px;
        display: flex;
        gap: 0.75rem;
    }
    .security-notice p { margin: 0; font-size: 0.75rem; color: #92400e; line-height: 1.4; }
</style>

<div class="password-wrapper">
    <a href="{{ route('petugas.profile') }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 5px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Profil
    </a>

    <div class="header-section">
        <h1>Ubah Password</h1>
        <p>Gunakan kombinasi yang kuat untuk keamanan akun Anda.</p>
    </div>

    @if ($message = Session::get('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #a7f3d0; font-size: 0.9rem; text-align: center;">
            <b>Sukses!</b> {{ $message }}
        </div>
    @endif

    <div class="glass-card">
        <form action="{{ route('petugas.update_password') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Password Saat Ini</label>
                <div class="password-field-container">
                    <input type="password" name="current_password" class="form-input" placeholder="••••••••" required>
                    <span class="toggle-password" onclick="toggleVisibility(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>
                @error('current_password') 
                    <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.4rem; display: block;">{{ $message }}</span> 
                @enderror
            </div>

            <div style="height: 1px; background: #f1f5f9; margin: 1.5rem 0;"></div>

            <div class="form-group">
                <label>Password Baru</label>
                <div class="password-field-container">
                    <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                    <span class="toggle-password" onclick="toggleVisibility(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>
                @error('password') 
                    <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.4rem; display: block;">{{ $message }}</span> 
                @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <div class="password-field-container">
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru" required>
                    <span class="toggle-password" onclick="toggleVisibility(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    🛡️ Perbarui Password
                </button>
                <a href="{{ route('petugas.profile') }}" class="btn btn-light">
                    Batal
                </a>
            </div>
        </form>

        <div class="security-notice">
            <span style="font-size: 1.2rem;">⚠️</span>
            <p>Pastikan password baru Anda sulit ditebak dan belum pernah digunakan sebelumnya untuk keamanan maksimal.</p>
        </div>
    </div>
</div>

<script>
    function toggleVisibility(el) {
        const input = el.parentElement.querySelector('input');
        const icon = el.querySelector('svg');
        
        if (input.type === 'password') {
            input.type = 'text';
            // Ikon Mata Tercoret (Hidden)
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
        } else {
            input.type = 'password';
            // Ikon Mata Terbuka (Show)
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        }
    }
</script>
@endsection
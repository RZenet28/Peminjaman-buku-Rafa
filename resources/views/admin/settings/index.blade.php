@extends('layouts.app')

@section('title', 'Pengaturan Admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Pengaturan Sistem</h2>
                <p class="text-muted mb-0">Kelola pengaturan aplikasi dan perpustakaan</p>
            </div>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Settings Navigation -->
            <div class="col-lg-3 mb-4">
                <div class="list-group">
                    <a href="#library-info" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                        <i class="bi bi-building me-2"></i>Informasi Perpustakaan
                    </a>
                    <a href="#borrowing-rules" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-gear me-2"></i>Aturan Peminjaman
                    </a>
                    <a href="#system-info" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-info-circle me-2"></i>Informasi Sistem
                    </a>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="col-lg-9">
                <div class="tab-content">
                    <!-- Library Info Section -->
                    <div class="tab-pane fade show active" id="library-info">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="fw-bold mb-4">Informasi Perpustakaan</h5>

                                <form method="POST" action="{{ route('admin.settings.update') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Perpustakaan</label>
                                        <input type="text" name="library_name"
                                            class="form-control @error('library_name') is-invalid @enderror"
                                            value="{{ old('library_name', $settings['library_name']) }}" required>
                                        @error('library_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alamat</label>
                                        <textarea name="library_address"
                                            class="form-control @error('library_address') is-invalid @enderror"
                                            rows="3">{{ old('library_address', $settings['library_address']) }}</textarea>
                                        @error('library_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Nomor Telepon</label>
                                            <input type="tel" name="library_phone"
                                                class="form-control @error('library_phone') is-invalid @enderror"
                                                value="{{ old('library_phone', $settings['library_phone']) }}">
                                            @error('library_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" name="library_email"
                                                class="form-control @error('library_email') is-invalid @enderror"
                                                value="{{ old('library_email', $settings['library_email']) }}">
                                            @error('library_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="fw-bold mb-4">Aturan Peminjaman</h5>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Periode Peminjaman (Hari)</label>
                                            <input type="number" name="borrowing_period"
                                                class="form-control @error('borrowing_period') is-invalid @enderror"
                                                value="{{ old('borrowing_period', $settings['borrowing_period']) }}" min="1"
                                                max="30" required>
                                            @error('borrowing_period')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Durasi standar peminjaman dalam hari</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Denda per Hari (Rp)</label>
                                            <input type="number" name="fine_per_day"
                                                class="form-control @error('fine_per_day') is-invalid @enderror"
                                                value="{{ old('fine_per_day', $settings['fine_per_day']) }}" min="0"
                                                required>
                                            @error('fine_per_day')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Biaya denda untuk setiap hari keterlambatan</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Buku Maksimal per Pengguna</label>
                                        <input type="number" name="max_books_per_user"
                                            class="form-control @error('max_books_per_user') is-invalid @enderror"
                                            value="{{ old('max_books_per_user', $settings['max_books_per_user']) }}" min="1"
                                            max="10" required>
                                        @error('max_books_per_user')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Jumlah buku maksimal yang dapat dipinjam sekaligus</small>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i>Simpan Pengaturan
                                        </button>
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- System Info Section -->
                    <div class="tab-pane fade" id="system-info">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="fw-bold mb-4">Informasi Sistem</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-muted">Versi Aplikasi</label>
                                        <p class="fw-bold">{{ config('app.version', '1.0.0') }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-muted">Versi Laravel</label>
                                        <p class="fw-bold">{{ app()->version() }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-muted">Versi PHP</label>
                                        <p class="fw-bold">{{ phpversion() }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-muted">Database</label>
                                        <p class="fw-bold">{{ config('database.default') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
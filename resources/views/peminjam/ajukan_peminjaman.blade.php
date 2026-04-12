@extends('layouts.peminjam')

@section('content')
    <style>
        /* Container & Layout */
        .peminjam-wrapper {
            padding: 40px;
            max-width: 800px;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .header-box {
            margin-bottom: 30px;
        }

        .header-box h2 {
            font-size: 28px;
            color: #2d3436;
            font-weight: 700;
            margin: 0;
        }

        .header-box p {
            color: #636e72;
            margin-top: 8px;
            font-size: 15px;
        }

        /* Card Styling */
        .form-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid #edf2f7;
        }

        /* Input Styling */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
        }

        .input-style {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 2px solid #edf2f7;
            background-color: #f8fafc;
            font-size: 15px;
            color: #2d3748;
            transition: all 0.3s ease;
            outline: none;
            box-sizing: border-box;
        }

        .input-style:focus {
            border-color: #6366f1;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* Info Section */
        .alert-info {
            background-color: #f5f3ff;
            border-left: 4px solid #6366f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .alert-info p {
            margin: 0;
            font-size: 13px;
            color: #5b21b6;
            line-height: 1.5;
        }

        /* Error Alert */
        .alert-error {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Buttons */
        .btn-container {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-submit {
            background-color: #6366f1;
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-cancel {
            text-decoration: none;
            color: #718096;
            font-size: 15px;
            font-weight: 500;
        }

        .btn-cancel:hover {
            color: #2d3748;
        }
    </style>

    <div class="peminjam-wrapper">
        <div class="header-box">
            <h2>Ajukan Peminjaman</h2>
            <p>Halo <strong>{{ auth()->user()->name }}</strong>, pilih koleksi buku yang ingin kamu baca.</p>
        </div>

        <div class="form-card">
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert-error">
                        <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #991b1b;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="buku_id">Pilih Judul Buku</label>
                    <select name="buku_id" id="buku_id" class="input-style" required>
                        <option value="" disabled selected>— Cari buku di sini —</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ old('buku_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->nama_buku }} (Stok: {{ $book->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tanggal_pinjam">Tanggal Mulai Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="input-style"
                        min="{{ date('Y-m-d') }}" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                </div>

                <div class="alert-info">
                    <p>
                        <strong>Informasi:</strong> Batas waktu peminjaman standar adalah 7 hari.
                        Pastikan mengembalikan buku tepat waktu untuk menghindari denda.
                    </p>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn-submit">✓ Konfirmasi Peminjaman</button>
                    <a href="{{ route('peminjam.dashboard') }}" class="btn-cancel">‹ Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
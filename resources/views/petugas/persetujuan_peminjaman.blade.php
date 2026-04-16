@extends('layouts.petugas')

@section('content')
    <style>
        .petugas-wrapper {
            padding: 40px;
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

        /* Alert Styling */
        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #155724;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #0c5460;
        }

        /* Loan Card Styling */
        .loan-cards {
            display: grid;
            gap: 20px;
        }

        .loan-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #eef0f6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .loan-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .loan-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .borrower-info h3 {
            font-size: 16px;
            color: #2d3436;
            font-weight: 600;
            margin: 0 0 5px 0;
        }

        .borrower-info p {
            font-size: 13px;
            color: #636e72;
            margin: 0;
        }

        .loan-status {
            display: inline-block;
            background-color: #fff3cd;
            color: #856404;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .loan-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef0f6;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .detail-value {
            font-size: 14px;
            color: #2d3436;
        }

        .book-title {
            font-weight: 600;
        }

        .loan-dates {
            display: flex;
            gap: 20px;
            font-size: 13px;
            color: #636e72;
        }

        .loan-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-approve {
            background-color: #28a745;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-approve:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-reject {
            background-color: #dc3545;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-reject:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px dashed #dee2e6;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .empty-state-title {
            font-size: 18px;
            color: #2d3436;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .empty-state-text {
            color: #636e72;
            font-size: 14px;
        }

        .pagination {
            margin-top: 30px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 4px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            color: #6366f1;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background-color: #6366f1;
            color: white;
        }

        .pagination .active {
            background-color: #6366f1;
            color: white;
            border-color: #6366f1;
        }
    </style>

    <div class="petugas-wrapper">
        <div class="header-box">
            <h2>Persetujuan Peminjaman</h2>
            <p>Kelola dan setujui permohonan peminjaman dari peminjam</p>
        </div>

        @if (session('success'))
            <div class="alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($loans->count() > 0)
            <div class="loan-cards">
                @foreach ($loans as $loan)
                    <div class="loan-card">
                        <div class="loan-header">
                            <div class="borrower-info">
                                <h3>{{ $loan->user->name }}</h3>
                                <p>{{ $loan->user->email }}</p>
                            </div>
                            <span class="loan-status">⏳ Menunggu Persetujuan</span>
                        </div>

                        <div class="loan-details">
                            <div class="detail-item">
                                <span class="detail-label">Judul Buku</span>
                                <span class="detail-value book-title">{{ $loan->buku->nama_buku ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Pengajuan Tanggal</span>
                                <span class="detail-value">{{ $loan->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal Mulai Pinjam</span>
                                <span class="detail-value">{{ $loan->tanggal_pinjam->format('d M Y') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal Kembali</span>
                                <span class="detail-value">{{ $loan->tanggal_kembali->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="loan-actions">
                            <form action="{{ route('petugas.approve', $loan->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-approve">✓ Setujui</button>
                            </form>
                            <form action="{{ route('petugas.reject', $loan->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-reject"
                                    onclick="return confirm('Apakah Anda yakin ingin menolak peminjaman ini?')">✗ Tolak</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($loans->hasPages())
                <div class="pagination">
                    {{ $loans->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">✓</div>
                <div class="empty-state-title">Tidak ada permohonan menunggu</div>
                <div class="empty-state-text">Semua permohonan peminjaman telah diproses</div>
            </div>
        @endif
    </div>
@endsection
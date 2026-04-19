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

        /* Request Card Styling */
        .request-cards {
            display: grid;
            gap: 20px;
        }

        .request-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #eef0f6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .request-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eef0f6;
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

        .request-badge {
            display: inline-block;
            background-color: #fff3cd;
            color: #856404;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .request-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef0f6;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 12px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .meta-value {
            font-size: 14px;
            color: #2d3436;
        }

        /* Books List */
        .books-section {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef0f6;
        }

        .books-title {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .books-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .book-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #6366f1;
        }

        .book-name {
            font-size: 14px;
            font-weight: 500;
            color: #2d3436;
            margin-bottom: 3px;
        }

        .book-count {
            font-size: 12px;
            color: #636e72;
        }

        .request-actions {
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
            <p>Kelola dan setujui permohonan peminjaman dari peminjam (dikelompokkan per pengajuan)</p>
        </div>

        @if (session('success'))
            <div class="alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($requests->count() > 0)
            <div class="request-cards">
                @foreach ($requests as $request)
                    <div class="request-card">
                        <div class="request-header">
                            <div class="borrower-info">
                                <h3>{{ $request->user->name }}</h3>
                                <p>{{ $request->user->email }}</p>
                            </div>
                            <span class="request-badge">⏳ Menunggu Persetujuan</span>
                        </div>

                        <div class="request-meta">
                            <div class="meta-item">
                                <span class="meta-label">Pengajuan ID</span>
                                <span class="meta-value">#{{ $request->id }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Tanggal Pengajuan</span>
                                <span class="meta-value">{{ $request->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Tanggal Mulai Pinjam</span>
                                <span class="meta-value">{{ $request->tanggal_pinjam->format('d M Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Tanggal Kembali</span>
                                <span class="meta-value">{{ $request->tanggal_kembali->format('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Books in this request -->
                        <div class="books-section">
                            <div class="books-title">📚 Buku yang Diminta ({{ $request->peminjamans->count() }} item)</div>
                            <div class="books-list">
                                @php
                                    $bookGrouped = $request->peminjamans->groupBy('buku_id');
                                @endphp
                                @foreach ($bookGrouped as $bookId => $pinjamans)
                                    @php
                                        $bookName = $pinjamans->first()->buku->nama_buku ?? 'N/A';
                                        $quantity = $pinjamans->count();
                                    @endphp
                                    <div class="book-item">
                                        <div class="book-name">{{ $bookName }}</div>
                                        <div class="book-count">× {{ $quantity }} {{ $quantity > 1 ? 'salinan' : 'salinan' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="request-actions">
                            <form action="{{ route('petugas.approve', $request->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-approve"
                                    onclick="return confirm('Setujui permohonan peminjaman ini? Stock buku akan berkurang.')">✓
                                    Setujui Semua</button>
                            </form>
                            <form action="{{ route('petugas.reject', $request->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-reject"
                                    onclick="return confirm('Apakah Anda yakin ingin menolak permohonan ini?')">✗ Tolak
                                    Semua</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($requests->hasPages())
                <div class="pagination">
                    {{ $requests->links() }}
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
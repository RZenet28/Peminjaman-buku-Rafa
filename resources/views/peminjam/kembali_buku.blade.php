@extends('layouts.peminjam')

@section('content')
    <style>
        .peminjam-wrapper {
            padding: 40px;
            max-width: 900px;
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
        .alert-info {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #0c5460;
        }

        /* Loan Cards */
        .loans-grid {
            display: grid;
            gap: 20px;
        }

        .loan-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px;
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
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .book-info h3 {
            font-size: 18px;
            color: #2d3436;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .book-info p {
            font-size: 13px;
            color: #636e72;
            margin: 0;
        }

        .status-badge {
            display: inline-block;
            background-color: #e7f5ff;
            color: #0066cc;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-overdue {
            background-color: #ffebee;
            color: #c62828;
        }

        /* Loan Details Grid */
        .loan-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 15px;
            color: #2d3436;
            font-weight: 500;
        }

        .detail-value.overdue {
            color: #dc3545;
            font-weight: 600;
        }

        /* Return Form */
        .return-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #6366f1;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            font-size: 13px;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-return {
            background-color: #6366f1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-return:hover {
            background-color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-cancel-form {
            background-color: #e9ecef;
            color: #495057;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel-form:hover {
            background-color: #dee2e6;
        }

        .form-group.toggle {
            margin-bottom: 0;
        }

        .toggle-btn {
            background-color: #e7f5ff;
            color: #0066cc;
            padding: 8px 16px;
            border: 1px solid #0066cc;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background-color: #0066cc;
            color: white;
        }

        /* Empty State */
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

        .return-form.hidden {
            display: none;
        }

        .warning-late {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            color: #856404;
            font-size: 13px;
        }
    </style>

    <div class="peminjam-wrapper">
        <div class="header-box">
            <h2>Kembalikan Buku</h2>
            <p>Kelola pengembalian buku yang telah Anda pinjam</p>
        </div>

        @if (session('success'))
            <div class="alert-info" style="background-color: #d4edda; border-left-color: #28a745; color: #155724;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($loans->count() > 0)
            <div class="loans-grid">
                @foreach ($loans as $loan)
                    @php
                        $isOverdue = $loan->tanggal_kembali < \Carbon\Carbon::now();
                        $daysUntilDue = \Carbon\Carbon::now()->diffInDays($loan->tanggal_kembali, false);
                    @endphp

                    <div class="loan-card">
                        <div class="loan-header">
                            <div class="book-info">
                                <h3>{{ $loan->buku->nama_buku }}</h3>
                                <p>Dipinjam: {{ $loan->tanggal_pinjam->format('d M Y') }}</p>
                            </div>
                            <span class="status-badge {{ $isOverdue ? 'status-overdue' : '' }}">
                                {{ $isOverdue ? '⚠ Terlambat' : '📚 Aktif' }}
                            </span>
                        </div>

                        <div class="loan-details">
                            <div class="detail-item">
                                <span class="detail-label">Batas Kembali</span>
                                <span class="detail-value {{ $isOverdue ? 'overdue' : '' }}">
                                    {{ $loan->tanggal_kembali->format('d M Y') }}
                                    @if ($isOverdue)
                                        <small>({{ abs($daysUntilDue) }} hari terlambat)</small>
                                    @elseif ($daysUntilDue <= 3)
                                        <small style="color: #ff9800;">({{ $daysUntilDue }} hari lagi)</small>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Denda (jika terlambat)</span>
                                <span class="detail-value">Rp 5.000 / hari</span>
                            </div>
                        </div>

                        @if ($isOverdue)
                            <div class="warning-late">
                                ⚠️ Buku ini sudah melewati batas waktu kembali. Silakan kembalikan secepatnya untuk menghindari denda.
                            </div>
                        @endif

                        <div class="return-form" id="form-{{ $loan->id }}">
                            <form action="{{ route('peminjam.proses_kembali', $loan->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="tanggal_pengembalian_{{ $loan->id }}">Tanggal Pengembalian</label>
                                    <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian_{{ $loan->id }}"
                                        value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="catatan_{{ $loan->id }}">Catatan (Opsional)</label>
                                    <textarea name="catatan" id="catatan_{{ $loan->id }}"
                                        placeholder="Contoh: Buku dalam kondisi baik...">{{ old('catatan') }}</textarea>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn-cancel-form" onclick="toggleForm({{ $loan->id }})">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn-return">
                                        ✓ Konfirmasi Pengembalian
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div style="text-align: right;" id="toggle-{{ $loan->id }}">
                            <button type="button" class="toggle-btn" onclick="toggleForm({{ $loan->id }})">
                                📥 Kembalikan Buku Ini
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <div class="empty-state-title">Tidak ada buku yang sedang dipinjam</div>
                <div class="empty-state-text">Semua buku telah dikembalikan atau belum ada peminjaman aktif</div>
            </div>
        @endif
    </div>

    <script>
        function toggleForm(id) {
            const form = document.getElementById('form-' + id);
            const toggle = document.getElementById('toggle-' + id);

            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                toggle.style.display = 'none';
            } else {
                form.classList.add('hidden');
                toggle.style.display = 'block';
            }
        }
    </script>
@endsection
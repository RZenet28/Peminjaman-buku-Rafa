@extends('layouts.app')

@section('title', 'Detail Riwayat Peminjaman')

@section('content')
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.history.index') }}" class="btn btn-outline-secondary mb-3">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                <h2 class="fw-bold mb-1">Detail Riwayat Peminjaman</h2>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0">Informasi Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small text-muted">ID Peminjaman</label>
                                <p class="fw-bold">#{{ $borrowing->id }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Status</label>
                                <p>
                                    @if($borrowing->status === 'dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @elseif($borrowing->status === 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $borrowing->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small text-muted">Tanggal Pinjam</label>
                                <p class="fw-bold">{{ $borrowing->tanggal_pinjam->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Tanggal Kembali (Seharusnya)</label>
                                <p class="fw-bold">{{ $borrowing->tanggal_kembali->format('d M Y') }}</p>
                            </div>
                        </div>

                        @if($borrowing->tanggal_pengembalian)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small text-muted">Tanggal Pengembalian (Aktual)</label>
                                    <p class="fw-bold">{{ $borrowing->tanggal_pengembalian->format('d M Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Keterlambatan</label>
                                    @php
                                        // Calculate days late properly using timestamp
                                        $actualTime = $borrowing->tanggal_pengembalian->timestamp;
                                        $expectedTime = $borrowing->tanggal_kembali->timestamp;

                                        $daysLate = 0;
                                        if ($actualTime > $expectedTime) {
                                            $secondsDiff = $actualTime - $expectedTime;
                                            $daysLate = (int) ceil($secondsDiff / (24 * 60 * 60));
                                        }
                                    @endphp
                                    @if($daysLate > 0)
                                        <p class="fw-bold text-warning">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            {{ $daysLate }} hari
                                        </p>
                                    @else
                                        <p class="fw-bold text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Tepat Waktu
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small text-muted">Denda</label>
                                    @php
                                        $denda = abs($borrowing->denda ?? 0); // Ensure positive
                                        $isDenda = $denda > 0;
                                    @endphp
                                    <p class="fw-bold {{ $isDenda ? 'text-danger' : 'text-success' }}">
                                        Rp {{ number_format($denda) }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editReturnDateModal">
                                        <i class="bi bi-pencil me-1"></i>Ubah
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($borrowing->catatan)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="fw-bold mb-0">Catatan</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ $borrowing->catatan }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Peminjam Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0">Informasi Peminjam</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">{{ $borrowing->user->name }}</h6>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-envelope me-2"></i>{{ $borrowing->user->email }}
                        </p>
                        <p class="small text-muted mb-0">
                            <i class="bi bi-tag me-2"></i>{{ $borrowing->user->role }}
                        </p>
                    </div>
                </div>

                <!-- Buku Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0">Informasi Buku</h5>
                    </div>
                    <div class="card-body">
                        @if($borrowing->buku->gambar)
                            <img src="{{ asset('storage/' . $borrowing->buku->gambar) }}"
                                alt="{{ $borrowing->buku->nama_buku }}" class="img-fluid rounded mb-3"
                                style="max-height: 150px;">
                        @endif
                        <h6 class="fw-bold mb-2">{{ $borrowing->buku->nama_buku }}</h6>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-tag me-2"></i>{{ $borrowing->buku->category->name ?? '-' }}
                        </p>
                        <p class="small text-muted mb-0">
                            <i class="bi bi-book me-2"></i>Stok: {{ $borrowing->buku->stock }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tanggal Pengembalian -->
    <div class="modal fade" id="editReturnDateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Tanggal Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editReturnDateForm">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian (Aktual)</label>
                            <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian"
                                value="{{ $borrowing->tanggal_pengembalian?->format('Y-m-d') }}" required>
                            <small class="text-muted d-block mt-2">
                                Tanggal Kembali (Seharusnya):
                                <strong>{{ $borrowing->tanggal_kembali?->format('d M Y') ?? '-' }}</strong>
                            </small>
                        </div>
                        <div class="alert alert-info" role="alert">
                            <small>
                                <strong>Info Denda:</strong> Akan dihitung otomatis berdasarkan pengaturan denda per hari.
                                <div class="mt-2" id="denda-preview"></div>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Store fine per day in a JavaScript variable
        const finePerDay = {{ $finePerDay }};
        const expectedReturnDate = new Date('{{ $borrowing->tanggal_kembali->format('Y-m-d') }}T00:00:00').getTime();

        document.getElementById('editReturnDateForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const tanggalPengembalian = document.getElementById('tanggal_pengembalian').value;
            const borrowingId = {{ $borrowing->id }};

            try {
                const response = await fetch(`/admin/history/${borrowingId}/return-date`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        tanggal_pengembalian: tanggalPengembalian
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message with updated fine info
                    alert(`Tanggal pengembalian berhasil diperbarui!\n\nTanggal: ${data.data.tanggal_pengembalian}\nHari Terlambat: ${data.data.daysLate} hari\nDenda: ${data.data.fineFormatted}`);

                    // Reload the page to see updated data
                    window.location.reload();
                } else {
                    alert('Gagal mengubah tanggal pengembalian');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });

        // Update preview when date changes
        document.getElementById('tanggal_pengembalian').addEventListener('change', function () {
            const selectedDate = new Date(this.value + 'T00:00:00').getTime();

            // Calculate difference in milliseconds, then convert to days
            let daysLate = 0;
            if (selectedDate > expectedReturnDate) {
                const millisDiff = selectedDate - expectedReturnDate;
                daysLate = Math.ceil(millisDiff / (1000 * 60 * 60 * 24));
            }

            const totalFine = Math.max(0, daysLate * finePerDay);

            const preview = document.getElementById('denda-preview');
            if (daysLate > 0) {
                preview.innerHTML = `<span class="badge bg-warning">${daysLate} hari terlambat = Rp ${totalFine.toLocaleString('id-ID')}</span>`;
            } else {
                preview.innerHTML = `<span class="badge bg-success">Tepat waktu, tidak ada denda</span>`;
            }
        });

        // Trigger preview on modal open
        document.getElementById('editReturnDateModal').addEventListener('show.bs.modal', function () {
            document.getElementById('tanggal_pengembalian').dispatchEvent(new Event('change'));
        });
    </script>
@endsection
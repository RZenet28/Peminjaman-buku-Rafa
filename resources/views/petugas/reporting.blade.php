@extends('layouts.petugas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Laporan Peminjaman</h1>
            <p class="text-gray-600">Generate dan cetak laporan peminjaman buku</p>
        </div>

        <!-- Date Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="{{ route('petugas.reporting') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Filter</button>
                </div>
            </form>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-gray-600 text-sm mb-2">Total Peminjaman</div>
                <p class="text-4xl font-bold text-green-600">{{ $totalBorrowings }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-gray-600 text-sm mb-2">Peminjaman Selesai</div>
                <p class="text-4xl font-bold text-blue-600">{{ $completedBorrowings }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-gray-600 text-sm mb-2">Peminjaman Terlambat</div>
                <p class="text-4xl font-bold text-red-600">{{ $lateBorrowings }}</p>
            </div>
        </div>

        <!-- Export Options -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Ekspor Laporan</h2>
            <form action="{{ route('petugas.export_report') }}" method="POST" class="flex flex-wrap gap-4">
                @csrf
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                    <select name="format"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="csv">CSV</option>
                        <option value="pdf">HTML/PDF</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        📥 Download Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- Top Books -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b bg-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Buku Paling Banyak Dipinjam</h2>
            </div>

            @if ($topBooks->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Judul Buku</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Penulis</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Jumlah Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topBooks as $idx => $book)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $idx + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ $book->nama_buku }}</td>
                                <td class="px-6 py-4 text-sm">{{ $book->pengarang ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-center font-semibold">{{ $book->peminjaman_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    <p class="text-lg">Tidak ada data peminjaman</p>
                </div>
            @endif
        </div>

        <!-- Top Borrowers -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Peminjam Paling Aktif</h2>
            </div>

            @if ($topBorrowers->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama Peminjam</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Jumlah Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topBorrowers as $idx => $borrower)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $idx + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ $borrower->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $borrower->email }}</td>
                                <td class="px-6 py-4 text-sm text-center font-semibold">{{ $borrower->peminjaman_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    <p class="text-lg">Tidak ada data peminjaman</p>
                </div>
            @endif
        </div>
    </div>
@endsection
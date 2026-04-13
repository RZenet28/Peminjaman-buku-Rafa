@extends('layouts.petugas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('petugas.books.index') }}" class="text-green-500 hover:text-green-600 mb-2 inline-block">&larr; Kembali ke Daftar Buku</a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $book->nama_buku }}</h1>
        <p class="text-gray-600">Detail dan riwayat peminjaman buku</p>
    </div>

    <!-- Book Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Book Info Card -->
        <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Buku</h2>
            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Judul:</span>
                    <span class="text-gray-600">{{ $book->nama_buku }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Pengarang:</span>
                    <span class="text-gray-600">{{ $book->pengarang ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Penerbit:</span>
                    <span class="text-gray-600">{{ $book->penerbit ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Tahun Terbit:</span>
                    <span class="text-gray-600">{{ $book->tahun ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">ISBN:</span>
                    <span class="text-gray-600">{{ $book->isbn ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Kategori:</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $book->category->name ?? '-' }}</span>
                </div>
                @if ($book->deskripsi)
                    <div class="border-b pb-2">
                        <span class="font-medium text-gray-700">Deskripsi:</span>
                        <p class="text-gray-600 mt-2">{{ $book->deskripsi }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Info Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Stok</h2>
            <div class="space-y-4">
                <div class="bg-gray-100 rounded-lg p-4 text-center">
                    <p class="text-gray-600 text-sm">Total Stok</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $book->stock ?? 0 }}</p>
                </div>
                <div class="bg-green-100 rounded-lg p-4 text-center">
                    <p class="text-gray-600 text-sm">Stok Tersedia</p>
                    <p class="text-3xl font-bold text-green-600">{{ $book->stock ?? 0 }}</p>
                </div>
                <div class="bg-orange-100 rounded-lg p-4 text-center">
                    <p class="text-gray-600 text-sm">Sedang Dipinjam</p>
                    <p class="text-3xl font-bold text-orange-600">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrowing History -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Riwayat Peminjaman</h2>
        </div>

        @if ($borrowingHistory->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Peminjam</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Peminjaman</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Kembali</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Pengembalian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowingHistory as $record)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $record->user->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $record->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm">{{ $record->tanggal_kembali->format('d-m-Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($record->status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">Menunggu</span>
                                @elseif ($record->status == 'dipinjam')
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">Dipinjam</span>
                                @elseif ($record->status == 'dikembalikan')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Dikembalikan</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ $record->tanggal_pengembalian ? $record->tanggal_pengembalian->format('d-m-Y H:i') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $borrowingHistory->links() }}
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-500">
                <p class="text-lg">Belum ada riwayat peminjaman</p>
            </div>
        @endif
    </div>
</div>
@endsection

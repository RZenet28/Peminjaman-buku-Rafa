@extends('layouts.petugas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('petugas.borrowers.index') }}" class="text-green-500 hover:text-green-600 mb-2 inline-block">&larr; Kembali ke Daftar Peminjam</a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $borrower->name }}</h1>
        <p class="text-gray-600">Detail profil dan riwayat peminjaman</p>
    </div>

    <!-- Profile Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Profile Card -->
        <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Profil</h2>
            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Nama:</span>
                    <span class="text-gray-600">{{ $borrower->name }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Email:</span>
                    <span class="text-gray-600">{{ $borrower->email }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Role:</span>
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ ucfirst($borrower->role) }}
                    </span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium text-gray-700">Bergabung Sejak:</span>
                    <span class="text-gray-600">{{ $borrower->created_at->format('d-m-Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Terakhir Update:</span>
                    <span class="text-gray-600">{{ $borrower->updated_at->format('d-m-Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Statistik Peminjaman</h2>
            <div class="space-y-4">
                <div class="bg-blue-100 rounded-lg p-4 text-center">
                    <p class="text-gray-600 text-sm">Sedang Dipinjam</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $activeLoans }}</p>
                </div>
                <div class="bg-green-100 rounded-lg p-4 text-center">
                    <p class="text-gray-600 text-sm">Total Peminjaman</p>
                    <p class="text-3xl font-bold text-green-600">{{ $totalLoans }}</p>
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Buku</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Peminjaman</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Kembali</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Pengembalian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowingHistory as $record)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $record->buku->nama_buku ?? 'N/A' }}</td>
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

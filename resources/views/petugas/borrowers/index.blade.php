@extends('layouts.petugas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Data Peminjam</h1>
            <p class="text-gray-600">Kelola data peminjam dan pantau riwayat peminjaman mereka</p>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ $message }}
            </div>
        @endif

        <!-- Search -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="{{ route('petugas.borrowers.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Cari</button>
                    <a href="{{ route('petugas.borrowers.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Reset</a>
                </div>
            </form>
        </div>

        <!-- Borrowers List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($borrowers->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Bergabung Sejak</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borrowers as $borrower)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium">{{ $borrower->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $borrower->email }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ ucfirst($borrower->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-center">{{ $borrower->created_at->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('petugas.borrowers.detail', $borrower->id) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t">
                    {{ $borrowers->links() }}
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    <p class="text-lg">Tidak ada peminjam ditemukan</p>
                </div>
            @endif
        </div>
    </div>
@endsection
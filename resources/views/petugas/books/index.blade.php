@extends('layouts.petugas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Data Buku</h1>
                <p class="text-gray-600">Kelola dan pantau data buku perpustakaan</p>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ $message }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="{{ route('petugas.books.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @if(request('kategori') == $cat) selected @endif>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul, penulis, ISBN..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Cari</button>
                    <a href="{{ route('petugas.books.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Reset</a>
                </div>
            </form>
        </div>

        <!-- Books List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($books->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Judul</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Penulis</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Kategori</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">ISBN</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Stok</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Tersedia</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium">{{ $book->nama_buku }}</td>
                                <td class="px-6 py-4 text-sm">{{ $book->pengarang ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                        {{ $book->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $book->isbn ?? '-' }}</td>
                                <td class="px-6 py-4 text-center text-sm font-semibold">{{ $book->stock ?? 0 }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    @if (($book->stock ?? 0) > 0)
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">
                                            {{ $book->stock ?? 0 }}
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">
                                            0
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('petugas.books.detail', $book->id) }}"
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
                    {{ $books->links() }}
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    <p class="text-lg">Tidak ada buku ditemukan</p>
                </div>
            @endif
        </div>
    </div>
@endsection
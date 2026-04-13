@extends('layouts.petugas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Monitor Pengembalian Buku</h1>
            <p class="text-gray-600">Pantau dan catat pengembalian buku dari peminjam</p>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ $message }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="{{ route('petugas.monitor_returns') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter</label>
                    <select name="filter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua</option>
                        <option value="overdue" @if(request('filter') == 'overdue') selected @endif>Terlambat</option>
                        <option value="today" @if(request('filter') == 'today') selected @endif>Hari Ini</option>
                        <option value="soon" @if(request('filter') == 'soon') selected @endif>3 Hari Ke Depan</option>
                    </select>
                </div>
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau buku..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Cari</button>
                    <a href="{{ route('petugas.monitor_returns') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Reset</a>
                </div>
            </form>
        </div>

        <!-- Returns List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($returns->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Peminjam</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Buku</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Peminjaman</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($returns as $return)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $return->user->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $return->buku->nama_buku ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $return->created_at->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($return->tanggal_kembali < now())
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ $return->tanggal_kembali->format('d-m-Y') }} (TERLAMBAT)
                                        </span>
                                    @else
                                        {{ $return->tanggal_kembali->format('d-m-Y') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Dipinjam
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="openReturnModal({{ $return->id }})"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                        Catat Pengembalian
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t">
                    {{ $returns->links() }}
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    <p class="text-lg">Tidak ada pengembalian buku yang perlu dicatat</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Return Modal -->
    <div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6">
            <h3 class="text-xl font-bold mb-4">Catat Pengembalian Buku</h3>

            <form id="returnForm" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Buku</label>
                    <select name="condition" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" placeholder="Catatan tambahan..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 h-24"></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                    <button type="button" onclick="closeReturnModal()"
                        class="flex-1 bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReturnModal(id) {
            const form = document.getElementById('returnForm');
            form.action = '/petugas/record-return/' + id;
            document.getElementById('returnModal').classList.remove('hidden');
        }

        function closeReturnModal() {
            document.getElementById('returnModal').classList.add('hidden');
        }
    </script>
@endsection
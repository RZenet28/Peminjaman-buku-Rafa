@extends('layouts.petugas')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Profil Saya</h1>
            <p class="text-gray-600">Kelola profil dan keamanan akun Anda</p>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Profil</h2>

                <form action="{{ route('petugas.update_profile') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <input type="text" value="{{ ucfirst($user->role) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bergabung Sejak</label>
                        <input type="text" value="{{ $user->created_at->format('d-m-Y H:i') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
                    </div>

                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Security Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Keamanan</h2>

                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm mb-2">Ubah Password</p>
                        <a href="{{ route('petugas.change_password') }}"
                            class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-center text-sm">
                            🔐 Ubah Password
                        </a>
                    </div>

                    <div class="bg-gray-100 rounded-lg p-3 text-sm text-gray-600">
                        <p class="font-semibold mb-1">Tips Keamanan:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Gunakan password yang kuat</li>
                            <li>Jangan bagikan password Anda</li>
                            <li>Ubah password secara berkala</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.dashboard')

@section('content')
<div class="w-full bg-emerald-300 py-12">
    <div class="w-full max-w-xl mx-auto bg-white p-8 rounded-lg shadow-xl border border-gray-200">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Pengaturan Akun</h2>

        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Foto Profil -->
            <div class="mb-6 text-center">
                <label for="profile_picture" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                <div class="mt-2">
                    <input type="file" id="profile_picture" name="profile_picture" class="w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg file:border-none file:bg-emerald-600 file:text-white file:cursor-pointer focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                </div>
                
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile Picture" class="mt-4 w-28 h-28 rounded-full border-4 border-emerald-600 object-cover mx-auto shadow-lg">
                @else
                    <!-- Gambar default icon orang abu-abu -->
                    <img src="https://www.iconfinder.com/icons/1674636/avatar_icon" alt="Default Profile Picture" class="mt-4 w-28 h-28 rounded-full border-4 border-emerald-600 object-cover mx-auto shadow-lg">
                @endif
            </div>

            <!-- Nama -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ auth()->user()->phone_number }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Password Baru -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300">
                Perbarui Akun
            </button>
        </form>
    </div>
</div>
@endsection

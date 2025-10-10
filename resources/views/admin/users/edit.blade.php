@extends('layouts.dashboard')

@section('content')
  <div class="container mx-auto px-4 pt-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
      📝 Edit Pengguna
    </h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" 
          class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200 space-y-5">
      @csrf
      @method('PUT')

      <!-- Nama -->
      <div>
        <label for="name" class="block text-sm font-semibold text-gray-700">
          🙍 Nama
        </label>
        <input type="text" name="name" id="name" 
               value="{{ old('name', $user->name) }}"
               class="mt-1 block w-full border border-indigo-200 bg-indigo-50 rounded-xl shadow-sm focus:ring-pink-400 focus:border-pink-400 px-3 py-2"
               required>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-semibold text-gray-700">
          📧 Email
        </label>
        <input type="email" name="email" id="email" 
               value="{{ old('email', $user->email) }}"
               class="mt-1 block w-full border border-green-200 bg-green-50 rounded-xl shadow-sm focus:ring-yellow-400 focus:border-yellow-400 px-3 py-2"
               required>
      </div>

      <!-- Role -->
      <div>
        <label for="role" class="block text-sm font-semibold text-gray-700">
          🎭 Role
        </label>
        <select name="role" id="role" 
                class="mt-1 block w-full border border-pink-200 bg-pink-50 rounded-xl shadow-sm focus:ring-purple-400 focus:border-purple-400 px-3 py-2">
          <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>👑 Admin</option>
          <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>👤 User</option>
          <option value="pemagang" {{ $user->role == 'pemagang' ? 'selected' : '' }}>🌱 Pemagang</option>
        </select>
      </div>

      <!-- Tombol -->
      <div class="flex gap-3">
        <button type="submit" 
                class="px-5 py-2 bg-gradient-to-r from-indigo-300 to-pink-300 text-gray-800 font-semibold rounded-full shadow hover:scale-105 transition transform">
          💾 Simpan Perubahan
        </button>
        <a href="{{ route('admin.users.index') }}"
           class="px-5 py-2 bg-gray-200 text-gray-700 rounded-full shadow hover:bg-gray-300 hover:scale-105 transition transform">
          ❌ Batal
        </a>
      </div>
    </form>
  </div>
@endsection

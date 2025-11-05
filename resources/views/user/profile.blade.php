@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-primary-300 py-10">
  <div class="max-w-7xl mx-auto p-6 bg-white rounded-3xl shadow-xl ring-1 ring-primary-200">

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="mb-4 bg-primary-50 text-primary-800 p-3 rounded-xl shadow-sm border border-primary-200">
        {{ session('success') }}
      </div>
    @elseif(session('error'))
      <div class="mb-4 bg-red-50 text-red-800 p-3 rounded-xl shadow-sm border border-red-200">
        {{ session('error') }}
      </div>
    @endif

    {{-- Profile Header --}}
    <div class="flex items-center gap-6 mb-8">
      <div class="w-16 h-16 sm:w-24 sm:h-24 md:w-32 md:h-32 rounded-full overflow-hidden ring-4 ring-primary-300 ring-offset-2 ring-offset-white">
        <img
          src="{{ asset('storage/' . (auth()->user()->profile_picture ?? 'default-avatar.png')) }}"
          alt="Profile Picture"
          class="w-full h-full object-cover"
        >
      </div>
      <div>
        <h2 class="text-3xl font-semibold text-primary-900">{{ auth()->user()->name }}</h2>
        <p class="text-zinc-600 text-lg">{{ auth()->user()->email }}</p>

        {{-- Role and Status (aman dari null) --}}
        <div class="mt-3 flex flex-wrap items-center gap-3">
          <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1.5 rounded-full bg-primary-100 text-primary-800 border border-primary-200">
            <i class="fa-solid fa-user-shield"></i>
            Role: <span class="font-semibold text-primary-900">{{ auth()->user()->role }}</span>
          </span>

          @php
            $internStatus = optional(auth()->user()->internshipRegistration)->internship_status ?? 'Not Registered';
            $statusColor  = match($internStatus) {
              'active'   => 'bg-primary-100 text-primary-800 border-primary-200',
              'inactive' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
              'ended'    => 'bg-zinc-100 text-zinc-800 border-zinc-200',
              default    => 'bg-zinc-100 text-zinc-700 border-zinc-200'
            };
          @endphp

          <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1.5 rounded-full border {{ $statusColor }}">
            <i class="fa-solid fa-circle-dot"></i>
            Status: <span class="font-semibold">{{ $internStatus }}</span>
          </span>
        </div>
      </div>
    </div>

    {{-- Profile Form --}}
    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @csrf
      @method('PUT')

      {{-- Name --}}
      <div class="md:col-span-1">
        <label for="name" class="block text-sm font-medium text-primary-800">Nama Lengkap</label>
        <input
          type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
          class="mt-1 p-3 w-full rounded-lg border border-primary-200 shadow-sm
                 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      {{-- Email --}}
      <div class="md:col-span-1">
        <label for="email" class="block text-sm font-medium text-primary-800">Email</label>
        <input
          type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
          class="mt-1 p-3 w-full rounded-lg border border-primary-200 shadow-sm
                 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      {{-- Phone Number --}}
      <div class="md:col-span-1">
        <label for="phone_number" class="block text-sm font-medium text-primary-800">Nomor Telepon</label>
        <input
          type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}"
          class="mt-1 p-3 w-full rounded-lg border border-primary-200 shadow-sm
                 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
        @error('phone_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      {{-- Password --}}
      <div class="md:col-span-1">
        <label for="password" class="block text-sm font-medium text-primary-800">Password Baru</label>
        <input
          type="password" id="password" name="password"
          class="mt-1 p-3 w-full rounded-lg border border-primary-200 shadow-sm
                 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      {{-- Confirm Password --}}
      <div class="md:col-span-1">
        <label for="password_confirmation" class="block text-sm font-medium text-primary-800">Konfirmasi Password</label>
        <input
          type="password" id="password_confirmation" name="password_confirmation"
          class="mt-1 p-3 w-full rounded-lg border border-primary-200 shadow-sm
                 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
        @error('password_confirmation') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      {{-- Profile Picture --}}
      <div class="md:col-span-2">
        <label for="profile_picture" class="block text-sm font-medium text-primary-800">Foto Profil</label>
        <input
          type="file" id="profile_picture" name="profile_picture" accept="image/*"
          class="mt-1 p-3 w-full rounded-lg border border-primary-200 shadow-sm
                 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
          onchange="previewImage()"
        >
        @error('profile_picture') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

        <div id="image-preview-container" class="mt-4 hidden">
          <img id="image-preview" class="w-32 h-32 object-cover rounded-xl ring-2 ring-primary-300" alt="Profile Picture Preview">
        </div>
      </div>

      {{-- Submit Button --}}
      <div class="md:col-span-2 mt-2 flex justify-end">
        <button
          type="submit"
          class="px-6 py-2.5 bg-primary-600 text-white rounded-xl shadow
                 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
        >
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Menampilkan preview gambar sebelum disubmit
  function previewImage() {
    const fileInput = document.getElementById('profile_picture');
    const file = fileInput?.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
      const imagePreview = document.getElementById('image-preview');
      const container = document.getElementById('image-preview-container');
      imagePreview.src = e.target.result;
      container.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }
</script>
@endsection

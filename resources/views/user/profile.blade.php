@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-emerald-300 py-10">
    <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 bg-green-200 text-green-800 p-3 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="mb-4 bg-red-200 text-red-800 p-3 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Profile Header --}}
        <div class="flex items-center space-x-6 mb-8">
            <div class="w-16 h-16 sm:w-24 sm:h-24 md:w-32 md:h-32 rounded-full overflow-hidden">
                <img src="{{ asset('storage/' . (auth()->user()->profile_picture ?? 'default-avatar.png')) }}" alt="Profile Picture" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-3xl font-semibold text-gray-800">{{ auth()->user()->name }}</h2>
                <p class="text-gray-600 text-lg">{{ auth()->user()->email }}</p>
            </div>
        </div>

        {{-- Profile Form --}}
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('name') 
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('email') 
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone Number --}}
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('phone_number') 
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input type="password" id="password" name="password" class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('password') 
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('password_confirmation') 
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Profile Picture --}}
                <div>
                    <label for="profile_picture" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" onchange="previewImage()">
                    @error('profile_picture') 
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                    <div id="image-preview-container" class="mt-4 hidden">
                        <img id="image-preview" class="w-32 h-32 object-cover rounded-lg" alt="Profile Picture Preview">
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Menampilkan preview gambar sebelum disubmit
    function previewImage() {
        const file = document.getElementById('profile_picture').files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('image-preview');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            imagePreview.src = e.target.result;
            imagePreviewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>

@endsection

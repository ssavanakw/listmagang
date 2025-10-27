@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Edit Feedback</h1>

    <form action="{{ route('admin.feedback.update', $feedback->id) }}" method="POST">
        @csrf
        @method('POST')

        <div class="mb-4">
            <label for="feedback" class="block text-sm font-medium mb-2">Feedback</label>
            <textarea id="feedback" name="feedback" rows="4" class="w-full p-2 border border-gray-300 rounded" required>{{ old('feedback', $feedback->feedback) }}</textarea>
            @error('feedback')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Update Feedback</button>
            <a href="{{ route('admin.feedback.index') }}" class="ml-4 text-gray-700">Kembali ke Daftar Feedback</a>
        </div>
    </form>
</div>
@endsection

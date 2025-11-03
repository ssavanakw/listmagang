{{-- resources/views/membercards/index.blade.php --}}

@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-4">Membercard Downloads</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">#</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">Name</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">Code</th> <!-- ✅ NEW -->
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">Angkatan</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">Instansi</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">Brand</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">Model URL</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">Downloaded</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-500">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($downloads as $download)
                    <tr class="border-b">
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $download->name }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $download->code ?? '-' }}</td> <!-- ✅ NEW -->
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $download->angkatan }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $download->instansi }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $download->brand }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700 truncate max-w-xs">
                            {{ $download->model_url ?? '-' }} <!-- ✅ Show model_url -->
                        </td>
                        <td class="py-2 px-4 text-sm text-gray-700">
                            @if ($download->has_downloaded)
                                <span class="text-green-500">Yes</span>
                            @else
                                <span class="text-red-500">No</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 text-sm text-gray-700 flex gap-2">
                            <a href="{{ route('admin.membercards.show', $download->code ?? 'MJ25067') }}" class="text-blue-600 hover:text-blue-800">View</a>
                            <a href="{{ route('admin.membercards.edit', $download->code ?? 'MJ25067') }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                            <form action="{{ route('admin.membercards.destroy', $download->code ?? 'MJ25067') }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

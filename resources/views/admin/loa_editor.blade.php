@extends('layouts.dashboard')

@section('title', 'Edit LOA Settings')

@section('content')
  @php
    use Carbon\Carbon;
  @endphp

  <div class="min-h-screen py-12 bg-gradient-to-b from-emerald-200 to-emerald-100">
    <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-2xl p-8 border border-emerald-100">
      
      <!-- Success/Error Toast Messages -->
      @if(session('success') || session('error'))
        <div class="mb-6 flex justify-center">
          <div class="flex items-center p-4 text-gray-700 bg-white rounded-xl shadow-md ring-1 ring-emerald-100">
            <svg class="w-5 h-5 mr-2 {{ session('success') ? 'text-emerald-600' : 'text-red-600' }}" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M16.707 5.293a1 1 0 0 0-1.414-1.414L8 11.172 4.707 7.879A1 1 0 0 0 3.293 9.293l4 4a1 1 0 0 0 1.414 0l8-8Z"/>
            </svg>
            <div class="text-sm font-medium">{{ session('success') ?? session('error') }}</div>
          </div>
        </div>
      @endif

      <div class="flex gap-6">
        <!-- LOA Settings Form -->
        <div class="w-1/2">
          <h2 class="text-2xl font-semibold text-emerald-700 mb-6">Edit LOA Settings</h2>
          
            <form action="{{ route('admin.loa.update', $loaSettings->id ?? 0) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Company Information -->
            <div class="mb-4">
                <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $loaSettings->company_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="company_address" class="block text-sm font-medium text-gray-700">Company Address</label>
                <input type="text" id="company_address" name="company_address" value="{{ old('company_address', $loaSettings->company_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="company_contact_email" class="block text-sm font-medium text-gray-700">Company Contact Email</label>
                <input type="email" id="company_contact_email" name="company_contact_email" value="{{ old('company_contact_email', $loaSettings->company_contact_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="company_contact_phone" class="block text-sm font-medium text-gray-700">Company Contact Phone</label>
                <input type="text" id="company_contact_phone" name="company_contact_phone" value="{{ old('company_contact_phone', $loaSettings->company_contact_phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="company_logo" class="block text-sm font-medium text-gray-700">Company Logo (optional)</label>
                <input type="file" id="company_logo" name="company_logo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <!-- Signature Information -->
            <div class="mb-4">
                <label for="signatory_name" class="block text-sm font-medium text-gray-700">Signatory Name</label>
                <input type="text" id="signatory_name" name="signatory_name" value="{{ old('signatory_name', $loaSettings->signatory_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="signatory_position" class="block text-sm font-medium text-gray-700">Signatory Position</label>
                <input type="text" id="signatory_position" name="signatory_position" value="{{ old('signatory_position', $loaSettings->signatory_position ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="signatory_image" class="block text-sm font-medium text-gray-700">Signatory Image (optional)</label>
                <input type="file" id="signatory_image" name="signatory_image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <!-- Dates -->
            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Internship Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $loaSettings->start_date ? \Carbon\Carbon::parse($loaSettings->start_date)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="end_date" class="block text-sm font-medium text-gray-700">Internship End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $loaSettings->end_date ? \Carbon\Carbon::parse($loaSettings->end_date)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="opening_greeting" class="block text-sm font-medium text-gray-700">Opening Greeting</label>
                <textarea id="opening_greeting" name="opening_greeting" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">{{ old('opening_greeting', $loaSettings->opening_greeting ?? '') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="closing_greeting" class="block text-sm font-medium text-gray-700">Closing Greeting</label>
                <textarea id="closing_greeting" name="closing_greeting" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">{{ old('closing_greeting', $loaSettings->closing_greeting ?? '') }}</textarea>
            </div>

            <button type="submit" class="mt-4 w-full py-2 px-4 bg-emerald-600 text-white rounded-md">Update LOA Settings</button>
            </form>

        </div>

        <!-- LOA Preview -->
        <div class="w-1/2">
          <h2 class="text-2xl font-semibold text-emerald-700 mb-6">Live LOA Preview</h2>

          <iframe id="loaPreview" src="{{ route('user.loa', ['id' => $loaSettings->id]) }}" class="w-full h-full border rounded-md"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- JS to update preview dynamically -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const previewIframe = document.getElementById('loaPreview');
      const form = document.querySelector('form');

      // Listen for form input changes
      form.addEventListener('input', function () {
        const formData = new FormData(form);
        fetch('{{ route('admin.loa.preview') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
          body: formData,
        })
        .then(response => response.json())
        .then(data => {
          previewIframe.contentWindow.postMessage(data, '*');
        });
      });
    });
  </script>

@endsection

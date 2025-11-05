@extends('layouts.dashboard')

@section('title', 'Pengaturan SKL')

@section('content')
<div class="grid md:grid-cols-2 gap-6 p-6">

  <!-- ===================== FORM SIDE ===================== -->
  <div class="max-w-xl">
    <h1 class="text-2xl font-bold mb-4 text-primary-700">📝 Pengaturan Surat Keterangan Magang (SKL)</h1>

    @if(session('success'))
      <div class="mb-4 bg-primary-50 border border-primary-200 text-primary-700 rounded-lg p-3">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.skl.update') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
          <input type="text" name="company_name" value="{{ old('company_name', $config['company_name']) }}"
                 class="w-full border border-primary-200 rounded-lg p-2 text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Alamat Perusahaan</label>
          <input type="text" name="company_address" value="{{ old('company_address', $config['company_address']) }}"
                 class="w-full border border-primary-200 rounded-lg p-2 text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Kota</label>
          <input type="text" name="company_city" value="{{ old('company_city', $config['company_city']) }}"
                 class="w-full border border-primary-200 rounded-lg p-2 text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Nama Pimpinan</label>
          <input type="text" name="leader_name" value="{{ old('leader_name', $config['leader_name']) }}"
                 class="w-full border border-primary-200 rounded-lg p-2 text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Jabatan Pimpinan</label>
          <input type="text" name="leader_title" value="{{ old('leader_title', $config['leader_title']) }}"
                 class="w-full border border-primary-200 rounded-lg p-2 text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
      </div>

      <hr class="my-4 border-primary-100">

      <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi Kegiatan</label>
        <textarea name="activity_description" rows="10"
                  class="w-full border border-primary-200 rounded-lg p-2 text-sm focus:ring-primary-500 focus:border-primary-500">{{ old('activity_description', $config->activity_description ?? 'Selama magang, yang bersangkutan menunjukkan sikap profesional, inisiatif tinggi, serta kemampuan bekerja dalam tim maupun secara mandiri. Ia menguasai berbagai keterampilan praktis yang mendukung bidangnya dan menyelesaikan seluruh tugas dengan baik serta tepat waktu.') }}</textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Pencapaian Peserta</label>
        <textarea name="participant_achievement" rows="10"
                  class="w-full border border-primary-200 rounded-lg p-2 text-sm focus:ring-primary-500 focus:border-primary-500">{{ old('participant_achievement', $config->participant_achievement ?? 'Peserta magang juga menunjukkan kemajuan signifikan dalam memahami proses operasional dan strategi perusahaan. Kontribusinya dihargai tim, terutama melalui ide-ide kreatif dan inovatif yang berhasil diterapkan di divisi tempat magang. Surat keterangan ini dibuat untuk digunakan sebagaimana mestinya dan sebagai bukti bahwa yang bersangkutan telah mengikuti dan menyelesaikan program magang dengan baik di perusahaan kami.') }}</textarea>
      </div>

      <hr class="my-4 border-primary-100">

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Upload Logo (opsional)</label>
          <input type="file" name="logo" accept="image/*" class="w-full border border-primary-200 rounded-lg p-2 text-sm">
          @if(Storage::disk('public')->exists('images/logos/logo_seveninc.png'))
            <img src="{{ asset('storage/images/logos/logo_seveninc.png') }}" class="h-16 mt-2 rounded shadow border border-primary-100" alt="Logo Saat Ini">
          @endif
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Upload Stempel (opsional)</label>
          <input type="file" name="stamp" accept="image/*" class="w-full border border-primary-200 rounded-lg p-2 text-sm">
          @if(Storage::disk('public')->exists('images/logos/stamp.png'))
            <img src="{{ asset('storage/images/logos/stamp.png') }}" class="h-16 mt-2 rounded shadow border border-primary-100" alt="Stempel Saat Ini">
          @endif
        </div>
      </div>

      <div class="flex justify-end gap-3 pt-4">
        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

  <!-- ===================== PREVIEW SIDE ===================== -->
  <div class="border rounded-lg overflow-hidden shadow-sm bg-white">
    <div class="p-3 border-b bg-primary-50 text-primary-700 font-semibold">
      Preview SKL
    </div>
    <iframe
      src="{{ route('admin.skl.preview') }}"
      style="width: 40vw; height: 100vh; border: none;"
    ></iframe>
  </div>
</div>

<script>
  // Menargetkan elemen iframe dan textarea untuk deskripsi dan pencapaian peserta
  const iframe = document.querySelector('iframe');  
  const descriptionInput = document.querySelector('textarea[name="activity_description"]');  
  const achievementInput = document.querySelector('textarea[name="participant_achievement"]');  

  // Fungsi untuk memperbarui live preview
  function refreshPreview() {
    const params = new URLSearchParams();
    if (descriptionInput) {
      params.append('activity_description', descriptionInput.value);
    }
    if (achievementInput) {
      params.append('participant_achievement', achievementInput.value);
    }
    iframe.src = "{{ route('admin.skl.preview') }}" + "?" + params.toString();
  }

  // Tambahkan event listener untuk update preview otomatis
  descriptionInput.addEventListener('input', () => {
    clearTimeout(window.__sklTypingDelay);
    window.__sklTypingDelay = setTimeout(refreshPreview, 400);
  });

  achievementInput.addEventListener('input', () => {
    clearTimeout(window.__sklTypingDelay);
    window.__sklTypingDelay = setTimeout(refreshPreview, 400);
  });
</script>
@endsection

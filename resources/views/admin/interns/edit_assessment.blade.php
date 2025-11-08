@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

  {{-- HEADER --}}
  <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        ✏️ Edit Penilaian Magang
      </h1>
      <a href="{{ route('interns.assessment.index') }}"
         class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-700 hover:bg-gray-800 rounded-lg shadow transition">
         ← Kembali
      </a>
  </div>

  {{-- ALERT --}}
  @if ($errors->any())
      <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-300" role="alert">
          <ul class="list-disc ml-5">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  @if(session('success'))
      <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-300" role="alert">
          {{ session('success') }}
      </div>
  @endif

  {{-- FORM --}}
  <form action="{{ route('interns.assessment.update', $assessment->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- FORM INPUT UTAMA --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="fullname" required value="{{ $assessment->fullname }}"
              class="block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">NIM / NIS</label>
            <input type="text" name="nim_or_nis" value="{{ $assessment->nim_or_nis }}"
              class="block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Program Studi</label>
            <input type="text" name="study_program" value="{{ $assessment->study_program }}"
              class="block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
        </div>

        {{-- ✅ Dropdown Divisi --}}
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Divisi / Kompetensi Keahlian</label>
            <select name="div" id="divisionSelect"
                class="block w-full rounded-lg border-gray-300 bg-white focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
                <option value="">-- Pilih Divisi --</option>
                <option value="Content Writer" {{ $assessment->div == 'Content Writer' ? 'selected' : '' }}>Content Writer</option>
                <option value="Programmer" {{ $assessment->div == 'Programmer' ? 'selected' : '' }}>Programmer</option>
                <option value="UI/UX Designer" {{ $assessment->div == 'UI/UX Designer' ? 'selected' : '' }}>UI/UX Designer</option>
                <option value="Graphic Designer" {{ $assessment->div == 'Graphic Designer' ? 'selected' : '' }}>Graphic Designer</option>
                <option value="Digital Marketing" {{ $assessment->div == 'Digital Marketing' ? 'selected' : '' }}>Digital Marketing</option>
                <option value="other">Lainnya...</option>
            </select>

            {{-- Input manual divisi --}}
            <input type="text" id="manualDivision"
                name="div_manual"
                placeholder="Masukkan divisi lain..."
                class="hidden mt-2 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" />
        </div>
    </div>

    {{-- TABLE PENILAIAN --}}
    <div class="border-t border-gray-300 pt-4">
      <h2 class="text-lg font-semibold text-gray-800 mb-3">Aspek Penilaian</h2>

      <div class="overflow-x-auto">
        <table id="tableAspek" class="w-full text-sm text-gray-700 border border-gray-200 rounded-lg">
          <thead class="text-xs uppercase bg-gray-100 border-b border-gray-200">
            <tr>
              <th class="px-4 py-3 text-center w-12">No</th>
              <th class="px-4 py-3">Aspek Penilaian</th>
              <th class="px-4 py-3 text-center w-28">Nilai</th>
              <th class="px-4 py-3 text-center w-12">Aksi</th>
            </tr>
          </thead>
          <tbody class="[&>tr:nth-child(odd)]:bg-white [&>tr:nth-child(even)]:bg-gray-50">
            @foreach($assessment->aspek_penilaian as $i => $item)
            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
              <td class="text-center font-medium text-gray-600">{{ $i + 1 }}</td>
              <td class="px-3 py-2">
                <input type="text" name="aspek[]" value="{{ $item['aspek'] }}"
                  class="block w-full p-2 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </td>
              <td class="px-3 py-2 text-center">
                <input type="number" name="nilai[]" value="{{ $item['nilai'] }}" min="0" max="100" oninput="updateAvg()"
                  class="block w-full text-center p-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </td>
              <td class="text-center">
                <button type="button" onclick="deleteRow(this)"
                  class="px-2.5 py-1 text-xs font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg shadow-sm transition-all">✖</button>
              </td>
            </tr>
            @endforeach
          </tbody>

          <tfoot class="bg-gray-50">
            <tr>
              <td colspan="2" class="text-right px-4 py-2 font-semibold">Rata-rata</td>
              <td class="text-center font-semibold text-blue-700" id="avg">{{ $assessment->rata_rata }}</td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>

      {{-- ACTION BUTTONS --}}
      <div class="mt-5 flex justify-between items-center">
        <button type="button" onclick="addRow()"
          class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-lg shadow-sm transition">
          ➕ Tambah Aspek
        </button>

        <button type="submit"
          class="inline-flex items-center gap-1 px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
          💾 Update Penilaian
        </button>
      </div>
    </div>
  </form>
</div>

{{-- SCRIPT --}}
<script>
  // ===== FUNGSI DASAR =====
  function updateNumbers() {
    document.querySelectorAll("#tableAspek tbody tr").forEach((r, i) => r.children[0].textContent = i + 1);
  }

  function updateAvg() {
    const inputs = document.querySelectorAll('input[name="nilai[]"]');
    let total = 0, count = 0;
    inputs.forEach(i => {
      const val = parseFloat(i.value);
      if (!isNaN(val)) { total += val; count++; }
    });
    document.getElementById('avg').textContent = count ? (total / count).toFixed(2) : '0';
  }

  function addRow() {
    const tbody = document.querySelector("#tableAspek tbody");
    const tr = document.createElement("tr");
    tr.classList.add("border-b", "border-gray-200", "hover:bg-gray-50");
    tr.innerHTML = `
      <td class="text-center font-medium text-gray-600"></td>
      <td class="px-3 py-2">
        <input type="text" name="aspek[]" value="Aspek Baru"
          class="block w-full p-2 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
      </td>
      <td class="px-3 py-2 text-center">
        <input type="number" name="nilai[]" value="0" min="0" max="100"
          oninput="updateAvg()"
          class="block w-full text-center p-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
      </td>
      <td class="text-center">
        <button type="button" onclick="deleteRow(this)"
          class="px-2.5 py-1 text-xs font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg shadow-sm transition-all">✖</button>
      </td>
    `;
    tbody.appendChild(tr);
    updateNumbers();
  }

  function deleteRow(btn) {
    btn.closest("tr").remove();
    updateNumbers();
    updateAvg();
  }

  // ===== LOAD ASPEK OTOMATIS BERDASARKAN DIVISI =====
  function loadAspekByDivision(division) {
    if (!division) return;
    fetch("{{ route('ajax.aspek') }}?division=" + division)
      .then(res => res.json())
      .then(data => {
        const tbody = document.querySelector("#tableAspek tbody");
        tbody.innerHTML = "";
        data.aspek.forEach((item, i) => {
          const tr = document.createElement("tr");
          tr.classList.add("border-b", "border-gray-200", "hover:bg-gray-50");
          tr.innerHTML = `
            <td class="text-center font-medium text-gray-600">${i + 1}</td>
            <td class="px-3 py-2">
              <input type="text" name="aspek[]" value="${item.aspek}"
                class="block w-full p-2 text-sm border-gray-300 rounded-lg">
            </td>
            <td class="px-3 py-2 text-center">
              <input type="number" name="nilai[]" value="0" min="0" max="100" oninput="updateAvg()"
                class="block w-full text-center p-2 border-gray-300 rounded-lg">
            </td>
            <td class="text-center">
              <button type="button" onclick="deleteRow(this)"
                class="px-2.5 py-1 text-xs text-white bg-red-500 hover:bg-red-600 rounded-lg shadow-sm">✖</button>
            </td>`;
          tbody.appendChild(tr);
        });
        updateNumbers();
        updateAvg();
      });
  }

  // ===== EVENT HANDLER DIVISI =====
  document.getElementById('divisionSelect').addEventListener('change', function () {
    const manualInput = document.getElementById('manualDivision');
    if (this.value === 'other') {
      manualInput.classList.remove('hidden');
      manualInput.focus();
    } else {
      manualInput.classList.add('hidden');
      manualInput.value = '';
      loadAspekByDivision(this.value);
    }
  });

  // ===== INIT =====
  updateNumbers();
  updateAvg();
</script>
@endsection

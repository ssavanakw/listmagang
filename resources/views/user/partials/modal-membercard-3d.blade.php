{{-- Modal Membercard 3D --}}
@php
  // URL publik ke GLB (via storage link)
  $membercardGlbUrl = asset('storage/models/Membercard.glb');
@endphp

<div id="modalMembercard3d" tabindex="-1" aria-hidden="true"
     class="hidden fixed inset-0 z-[999] bg-black/70 backdrop-blur-sm items-center justify-center">
  <div class="relative w-full max-w-5xl p-4">
    <div class="relative rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10"
         style="background: radial-gradient(1200px 600px at 50% -20%, #111827 0%, #0b0f1a 40%, #06090f 100%);">

      {{-- Header --}}
      <div class="flex items-center justify-between px-5 py-3 border-b border-white/10">
        <h2 class="text-sm sm:text-base font-semibold text-white/90">Preview Membercard 3D</h2>
        <div class="flex items-center gap-2">
          <a id="download3dButton" href="{{ $membercardGlbUrl }}" download="Membercard.glb"
             class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/15 text-white text-xs font-medium transition">
            Download .glb
          </a>
          <button type="button" data-modal-hide="modalMembercard3d"
                  class="text-white/70 hover:text-white focus:outline-none text-xl leading-none">✕</button>
        </div>
      </div>

      {{-- Canvas Area --}}
      <div class="relative">
        <div id="membercard3dCanvas"
             class="w-full h-[480px] md:h-[560px] bg-[#0a0f18] select-none"
             data-model-url="{{ $membercardGlbUrl }}"
             data-name="{{ $reg->fullname ?? $user->name }}"
             data-id="{{ $reg->code ?? 'MJ25067' }}"
             data-angkatan="{{ $reg->angkatan ?? '2025' }}"
             data-instansi="{{ $reg->institution_name ?? 'UNIVERSITAS AHMAD DAHLAN' }}"
             data-brand="magangjogja.com">
          <div class="absolute inset-0 grid place-items-center text-white/60 text-xs">
            Loading 3D Viewer…
          </div>
        </div>

        {{-- footer subtle --}}
        <div class="px-5 py-3 border-t border-white/5 text-[11px] text-white/50">
          Scroll untuk zoom, drag untuk orbit, klik kanan untuk pan.
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Inject bundel viewer --}}
@vite('resources/js/membercard-3d.js')

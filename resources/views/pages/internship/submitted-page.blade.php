<!-- resources/views/pages/internship/submitted-page.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pendaftaran Terkirim</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-emerald-300 min-h-screen flex items-center justify-center p-6">
  <main class="w-full max-w-xl">
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 text-center">
      <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-emerald-600">
          <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-2.59a.75.75 0 1 0-1.12-.996l-3.61 4.06-1.52-1.52a.75.75 0 0 0-1.06 1.06l2.1 2.1a.75.75 0 0 0 1.09-.03l4.12-4.67Z" clip-rule="evenodd" />
        </svg>
      </div>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-2">Berhasil Dikirim 🎉</h1>
      <p class="text-zinc-600 mb-6">Terima kasih sudah mendaftar Magang/PKL. Data kamu sudah kami terima. Kami akan menghubungi kamu melalui email/WhatsApp jika ada informasi lanjutan.</p>

      <div class="space-y-3">
        @if (auth()->user()->role === 'pemagang' && auth()->user()->internshipRegistration->internship_status === 'active')
          <a href="{{ route('user.dashboard-active') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300">
          Ke Beranda
          </a>
        @else
          <a href="{{ route('user.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300">
          Ke Beranda
          </a>
        @endif

        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('user.logout') }}" class="mt-2">
          @csrf
          <button type="submit" 
            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg 
                  bg-red-600 text-white font-medium shadow hover:bg-red-700 
                  focus:outline-none focus:ring-4 focus:ring-red-300 transition">
            <!-- Icon Logout -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m12-6l6 6-6 6"/>
            </svg>
            Logout
          </button>
        </form>

        <div>
          <a href="{{ route('internship.submitted') }}" class="text-sm text-zinc-500 hover:text-zinc-700">Halaman ini</a>
          <span class="text-sm text-zinc-400">adalah bukti bahwa kamu sudah mengirim formulir.</span>
        </div>
      </div>
    </div>
  </main>
</body>
</html>

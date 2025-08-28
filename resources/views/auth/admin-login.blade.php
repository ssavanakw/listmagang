<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  @vite(['resources/css/app.css','resources/js/app.js'])

  {{-- Auto dark sesuai sistem (Tailwind darkMode: "class") --}}
  <script>
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      document.documentElement.classList.add('dark');
    }
  </script>
</head>
<body class="min-h-screen flex items-center justify-center bg-emerald-500
             bg-gradient-to-br from-emerald-400 via-emerald-500 to-emerald-700
             dark:from-emerald-900 dark:via-emerald-950 dark:to-black">

  <div class="w-full max-w-md px-4">
    <div class="rounded-2xl shadow-xl ring-1 ring-black/5 dark:ring-white/10
                bg-white/90 dark:bg-gray-900/85 backdrop-blur p-6 sm:p-8">

      <h2 class="mb-6 text-2xl font-bold text-center text-gray-900 dark:text-gray-100">
        Admin Login
      </h2>

      @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg border
                    bg-red-50 text-red-700 border-red-200
                    dark:bg-red-950/40 dark:text-red-200 dark:border-red-900/50">
          {{ session('error') }}
        </div>
      @endif

      <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Email</label>
          <input type="email" id="email" name="email" required autocomplete="username"
                 placeholder="you@example.com"
                 class="mt-1 w-full px-4 py-2 rounded-lg border
                        bg-white text-gray-900 placeholder-gray-400
                        border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                        dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 dark:border-gray-700 dark:focus:ring-emerald-400 dark:focus:border-emerald-400"/>
        </div>

        <!-- Password -->
        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Password</label>
            <button type="button" id="togglePassword" class="text-xs text-emerald-700 hover:underline dark:text-emerald-300">Show</button>
          </div>
          <input type="password" id="password" name="password" required autocomplete="current-password"
                 placeholder="••••••••"
                 class="mt-1 w-full px-4 py-2 rounded-lg border
                        bg-white text-gray-900 placeholder-gray-400
                        border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                        dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 dark:border-gray-700 dark:focus:ring-emerald-400 dark:focus:border-emerald-400"/>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
          <input type="checkbox" id="remember" name="remember"
                 class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500
                        dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-emerald-400"/>
          <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Remember me</label>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full inline-flex justify-center items-center gap-2
                       bg-emerald-600 hover:bg-emerald-700 text-white font-medium
                       py-2.5 px-4 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500
                       dark:focus:ring-offset-gray-900">
          Login
        </button>
      </form>

      <p class="mt-6 text-center text-xs text-emerald-900/80 dark:text-emerald-200/70">
        Secured area — authorized users only.
      </p>
    </div>
  </div>

  <script>
    // show/hide password (tetap ada)
    const pw = document.getElementById('password');
    const pwBtn = document.getElementById('togglePassword');
    pwBtn.addEventListener('click', () => {
      const show = pw.type === 'text';
      pw.type = show ? 'password' : 'text';
      pwBtn.textContent = show ? 'Show' : 'Hide';
    });
  </script>
</body>
</html>

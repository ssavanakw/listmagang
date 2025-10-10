<!DOCTYPE html>
<html lang="en" class="dark overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="#">
    <meta name="author" content="#">
    <meta name="generator" content="Laravel">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - </title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="canonical" href="{{ request()->fullUrl() }}">

    @if(isset($page->params['robots']))
        <meta name="robots" content="{{ $page->params['robots'] }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" href="/favicon.ico">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@">
    <meta name="twitter:creator" content="@">
    <meta name="twitter:title" content="title">
    <meta name="twitter:description" content="description">
    <meta name="twitter:image" content="#">
    <!-- Facebook -->
    <meta property="og:url" content="#">
    <meta property="og:title" content="title">
    <meta property="og:description" content="description">
    <meta property="og:type" content="website">
    <meta property="og:image" content="#">
    <meta property="og:image:type" content="image/png">

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
@php
    $whiteBg = isset($params['white_bg']) && $params['white_bg'];
@endphp
<body class="{{ $whiteBg ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} overflow-x-hidden">
    <x-navbar-dashboard/>
    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        <x-sidebar.admin-sidebar/>
        <!-- Penting: min-w-0 & overflow-x-hidden agar konten lebar tidak membuat scrollbar global -->
        <div id="main-content" class="relative w-full h-full min-w-0 overflow-y-auto overflow-x-hidden bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <main class="min-w-0">
                @yield('content')
            </main>
            <x-footer-dashboard/>
        </div>
    </div>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script>

    @stack('modals')
    @stack('scripts')
    @if (session('success') || session('mail_info'))
    <div
    id="toast"
    class="fixed top-4 right-4 z-50 w-80 rounded-lg shadow-lg bg-white border border-gray-200 overflow-hidden"
    >
    <div class="px-4 py-3 border-b border-gray-100 font-semibold text-green-700">
        {{ session('success') ?? 'Notifikasi' }}
    </div>

    @if (session('mail_info'))
        @php $info = session('mail_info'); @endphp
        <div class="px-4 py-3 text-sm text-gray-700">
        <div class="font-medium mb-1">{{ $info['title'] ?? 'Email notifikasi' }}</div>

        @if (!empty($info['to']) && !empty($info['name']))
            <div class="mt-1">
            Dikirim ke: <span class="font-mono">{{ $info['to'] }}</span><br>
            Penerima: <span class="font-medium">{{ $info['name'] }}</span>
            </div>
        @endif

        @if (!empty($info['list']))
            <ul class="mt-2 list-disc list-inside max-h-40 overflow-auto">
            @foreach ($info['list'] as $row)
                <li><span class="font-medium">{{ $row['name'] }}</span> — <span class="font-mono">{{ $row['to'] }}</span></li>
            @endforeach
            </ul>
        @endif
        </div>
    @endif

    <button onclick="document.getElementById('toast')?.remove()"
            class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
        ✕
    </button>
    </div>
    <script>
    setTimeout(() => { document.getElementById('toast')?.remove(); }, 5000);
    </script>
    @endif


</body>
</html>

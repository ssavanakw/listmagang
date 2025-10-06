<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Layout</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
  <script src="https://kit.fontawesome.com/a2d9d5f1e2.js" crossorigin="anonymous"></script>

  <style>
  [data-dropdown-toggle] + div {
    opacity: 0;
    transform: translateY(-5px);
    transition: all 0.2s ease;
  }
  [data-dropdown-toggle].active + div {
    opacity: 1;
    transform: translateY(0);
  }
</style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

  <!-- ===== NAVBAR ===== -->
  <nav class="fixed top-0 left-0 right-0 z-40 bg-white dark:bg-gray-800 shadow">
    <div class="flex justify-between items-center px-6 py-3">
      <div class="font-semibold text-lg">My Dashboard</div>

      <div class="flex items-center gap-4">
        <!-- Notifikasi -->
        <button id="notifBtn" data-dropdown-toggle="notifDropdown" class="relative text-gray-600 dark:text-gray-200">
          <i class="fa-regular fa-bell text-xl"></i>
          <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">3</span>
        </button>

        <!-- Dropdown Notifikasi -->
        <div id="notifDropdown" class="hidden absolute right-6 mt-2 w-72 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
          <div class="px-4 py-2 font-semibold border-b dark:border-gray-700">Notifikasi</div>
          <ul class="max-h-64 overflow-y-auto">
            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">📦 Barang baru ditambahkan</li>
            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">⚠️ Stok menipis di gudang</li>
            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">✅ Transaksi keluar berhasil</li>
            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">🕒 Jadwal pengiriman hari ini</li>
          </ul>
          <div class="px-4 py-2 text-center">
            <a href="#" class="text-blue-600 hover:underline text-sm">Lihat semua notifikasi</a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- ===== SIDEBAR ===== -->
  <aside class="fixed top-0 left-0 h-full w-56 bg-white dark:bg-gray-800 shadow-md pt-16">
    <ul class="space-y-2 p-4">
      <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">🏠 Dashboard</a></li>
      <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">📦 Produk</a></li>
      <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">📊 Laporan</a></li>
      <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">⚙️ Pengaturan</a></li>
    </ul>
  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="ml-56 pt-16 p-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
      <h1 class="text-2xl font-semibold mb-4">Selamat datang di Dashboard!</h1>
      <p>Ini area konten utama. Klik ikon 🔔 di kanan atas untuk melihat dropdown notifikasi seperti sketsa kamu.</p>
    </div>
  </main>
<script>
  // Tambahkan efek animasi saat toggle Flowbite dipakai
  document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
    btn.addEventListener('click', () => btn.classList.toggle('active'));
  });
</script>
</body>
</html>

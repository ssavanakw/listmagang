<x-sidebar-dashboard>
    {{-- Dashboard Admin --}}
    <x-sidebar-menu-dashboard 
        routeName="dashboard.index" 
        title="Dashboard"/>

    {{-- Data Magang Dropdown --}}
    <x-sidebar-menu-dropdown-dashboard routeName="internship.*" title="Data Magang">
        <x-sidebar-menu-dropdown-item-dashboard 
            routeName="internship.table" 
            title="Tabel Pendaftar"/>
        <x-sidebar-menu-dropdown-item-dashboard 
            routeName="internship.form" 
            title="Form Pendaftaran"/>
    </x-sidebar-menu-dropdown-dashboard>

    {{-- Tombol Logout di bagian bawah --}}
    <div class="mt-auto px-4 pb-4">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" 
                class="w-full flex items-center justify-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-5 w-5" 
                     fill="none" 
                     viewBox="0 0 24 24" 
                     stroke="currentColor">
                    <path stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</x-sidebar-dashboard>

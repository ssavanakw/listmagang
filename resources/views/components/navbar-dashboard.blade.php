<nav class="fixed z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="p-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg id="toggleSidebarMobileClose" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>                    
                <a href="/admin/dashboard" class="flex ml-2 md:mr-24">
                    <img src="{{ asset('storage/images/logos/logo_seveninc.png') }}" class="h-11 mr-3" alt="Internship Admin Logo" />
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                            Internship Admin
                        </span>
                    @else
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                            Internship
                        </span>
                    @endif
                </a>
                <form action="#" method="GET" class="hidden lg:block lg:pl-3.5">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative mt-1 lg:w-96">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input type="text" name="email" id="topbar-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search">
                    </div>
                </form>
            </div>

            <div class="relative">
                <!-- Profile Picture Button -->
                <button id="profilePictureButton" class="flex items-center gap-2 px-3 py-2 text-gray-600 rounded-lg hover:bg-gray-200 focus:outline-none">
                    <img src="{{ asset('storage/' . (auth()->user()->profile_picture ?? 'default-avatar.png')) }}" alt="User Profile" class="w-10 h-10 rounded-full border-2 border-primary-600 object-cover">
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdown"
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0; pointer-events: none;">
                    <ul class="py-1">
                        <li><a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a></li>
                        <li><a href="{{ route('user.logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const button = document.getElementById("profilePictureButton");
        const dropdown = document.getElementById("profileDropdown");

        button.addEventListener("click", function (e) {
            e.stopPropagation();
            const isVisible = dropdown.style.maxHeight && dropdown.style.maxHeight !== "0px";

            if (!isVisible) {
                dropdown.style.maxHeight = "200px";
                dropdown.style.opacity = "1";
                dropdown.style.pointerEvents = "auto";
            } else {
                dropdown.style.maxHeight = "0";
                dropdown.style.opacity = "0";
                dropdown.style.pointerEvents = "none";
            }
        });

        // Close on outside click
        document.addEventListener("click", function (e) {
            if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.style.maxHeight = "0";
                dropdown.style.opacity = "0";
                dropdown.style.pointerEvents = "none";
            }
        });
    });
</script>



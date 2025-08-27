<!-- resources/views/internship-registration/form.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pendaftaran Magang/PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-emerald-400 min-h-screen flex items-center justify-center">
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl my-6">
    <section class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900">
            Form Pendaftaran Magang/PKL
        </h2>
        <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 text-gray-400 sm:text-xl">
            Silahkan isi form pendaftaran ini dengan lengkap untuk memudahkan kami dalam memproses. Terimakasih.
        </p>

        <form action="{{ route('internship.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- fullname -->
            <div>
                <label for="fullname" class="block mb-2 text-sm font-medium text-gray-900 text-gray-300">Nama Lengkap</label>
                <input type="text" id="fullname" name="fullname" required placeholder="Your answer"
                       class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
            </div>

            <!-- born_date -->
            <div class="mb-3">
                <label for="born_date" class="block mb-2 text-sm font-medium text-gray-900">Tahun Lahir</label>
                <input 
                    datepicker 
                    datepicker-autohide 
                    type="text" 
                    id="born_date" 
                    name="born_date" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" 
                    placeholder="Select date" 
                    required
                >
            </div>

          {{-- Student ID --}}
            <div class="mb-3">
                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900">
                    Nomor Induk Mahasiswa / Nomor Induk Siswa
                </label>
                <input 
                    type="text" 
                    id="student_id" 
                    name="student_id" 
                    class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                        shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                    placeholder="Your student ID" 
                    required
                >
            </div>

            {{-- Email --}}
            <div class="mb-3">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
                Email
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                    focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" 
                placeholder="surya@example.com" 
                required
            >
            </div>

            {{-- Gender --}}
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin</h3>
                <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg">
                    <li class="w-full border-b border-gray-300 rounded-t-lg">
                    <div class="flex items-center ps-3">
                        <input 
                        id="list-radio-male-id" 
                        type="radio" 
                        value="male" 
                        name="gender" 
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                        >
                        <label for="list-radio-male-id" class="w-full py-3 ms-2 text-sm font-medium text-gray-900">
                        Laki-laki
                        </label>
                    </div>
                    </li>
                    <li class="w-full border-b border-gray-300 rounded-t-lg">
                    <div class="flex items-center ps-3">
                        <input 
                        id="list-radio-female-id" 
                        type="radio" 
                        value="female" 
                        name="gender" 
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                        >
                        <label for="list-radio-female-id" class="w-full py-3 ms-2 text-sm font-medium text-gray-900">
                        Perempuan
                        </label>
                    </div>
                    </li>
                </ul>
            </div>

            {{-- Active phone number or WhatsApp number --}}
            <div class="mb-3">
                <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900">
                    No. HP Aktif yang bisa dihubungi dan chat WA
                </label>
                <input 
                    type="text" 
                    id="phone_number" 
                    name="phone_number" 
                    class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                        shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                    placeholder="Your answer" 
                    required
                >
            </div>

            {{-- Institution Name --}}
            <div class="mb-3">
                <label for="institution_name" class="block mb-2 text-sm font-medium text-gray-900">
                    Asal Sekolah/Kampus
                </label>
                <input 
                    type="text" 
                    id="institution_name" 
                    name="institution_name" 
                    class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                        shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                    placeholder="Your answer" 
                    required
                >
            </div>

            {{-- Study Program --}}
            <div class="mb-3">
                <label for="study_program" class="block mb-2 text-sm font-medium text-gray-900">
                    Program Studi
                </label>
                <input 
                    type="text" 
                    id="study_program" 
                    name="study_program" 
                    class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                        shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                    placeholder="Your answer" 
                    required
                >
            </div>

            {{-- Faculty --}}
            <div class="mb-3">
                <label for="faculty" class="block mb-2 text-sm font-medium text-gray-900">
                    Fakultas
                </label>
                <input 
                    type="text" 
                    id="faculty" 
                    name="faculty" 
                    class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                        shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                    placeholder="Your answer" 
                    required
                >
            </div>

            {{-- Current City/Region --}}
            <div class="mb-3">
                <label for="current_city" class="block mb-2 text-sm font-medium text-gray-900">
                    Nama Kota/Daerah tempat tinggal anda saat ini
                </label>
                <input 
                    type="text" 
                    id="current_city" 
                    name="current_city" 
                    class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                        shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                    placeholder="Your answer" 
                    required
                >
            </div>
            {{-- Internship Reason --}}
            <div class="mb-3">
            <label for="internship_reason" class="block mb-2 text-sm font-medium text-gray-900">
                Mengapa Anda ingin Magang/PKL disini?
            </label>
            <input 
                type="text" 
                id="internship_reason" 
                name="internship_reason" 
                class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                    shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                placeholder="Your answer" 
                required
            >
            </div>

            {{-- Internship Type --}}
            <div class="mb-3">
            <h3 class="block mb-2 text-sm font-medium text-gray-900">
                Jenis Magang apa yang Anda pilih?
            </h3>
            <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg">
                <li class="w-full border-b border-gray-300 rounded-t-lg">
                <div class="flex items-center ps-3">
                    <input 
                    id="list-radio-self-internship-id" 
                    type="radio" 
                    value="self" 
                    name="internship_type" 
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="list-radio-self-internship-id" class="w-full py-3 ms-2 text-sm font-medium text-gray-900">
                    Magang Mandiri (magang atas inisiatif diri sendiri, bukan kewajiban dari kampus)
                    </label>
                </div>
                </li>
                <li class="w-full border-b border-gray-300">
                <div class="flex items-center ps-3">
                    <input 
                    id="list-radio-university-required-id" 
                    type="radio" 
                    value="university" 
                    name="internship_type" 
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="list-radio-university-required-id" class="w-full py-3 ms-2 text-sm font-medium text-gray-900">
                    Magang Kampus / Reguler (magang karena kewajiban dari kampus)
                    </label>
                </div>
                </li>
            </ul>
            </div>

            {{-- Internship Arrangement --}}
            <div class="mb-3">
            <h3 class="block mb-2 text-sm font-medium text-gray-900">
                Saya Siap dengan Sistem Magang ini:
            </h3>
            <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg">
                <li class="w-full border-b border-gray-300 rounded-t-lg">
                <div class="flex items-center ps-3">
                    <input 
                    id="list-radio-wfo" 
                    type="radio" 
                    value="wfo" 
                    name="internship_arrangement" 
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="list-radio-wfo" class="w-full py-3 ms-2 text-sm font-medium text-gray-900">
                    WFO (Work From Office)
                    </label>
                </div>
                </li>
            </ul>
            </div>
            {{-- Current Status --}}
            <div class="mb-5">
                <h3 class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Status Anda saat ini:
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-300 last:border-0 dark:border-gray-600">
                        <div class="flex items-center p-3">
                            <input id="status-school-or-college" type="radio" value="school-or-college" name="current_status"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="status-school-or-college" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Masih sekolah/masih kuliah
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-300 last:border-0 dark:border-gray-600">
                        <div class="flex items-center p-3">
                            <input id="status-unemployed" type="radio" value="unemployed" name="current_status"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="status-unemployed" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Sudah lulus sekolah/kuliah dan belum bekerja
                            </label>
                        </div>
                    </li>
                    <li class="w-full dark:border-gray-600">
                        <div class="flex items-center p-3">
                            <input id="status-employed" type="radio" value="employed" name="current_status"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="status-employed" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Sudah lulus dan sedang bekerja
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Read English Book --}}
            <div class="mb-5">
                <h3 class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Apakah Anda bisa membaca buku berbahasa Inggris?
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-300 last:border-0 dark:border-gray-600">
                        <div class="flex items-center p-3">
                            <input id="english-ican" type="radio" value="ican" name="english_book_ability"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="english-ican" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Saya Bisa
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-300 last:border-0 dark:border-gray-600">
                        <div class="flex items-center p-3">
                            <input id="english-limited" type="radio" value="limited-ability" name="english_book_ability"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="english-limited" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Saya Kurang Bisa (bisa sedikit-sedikit)
                            </label>
                        </div>
                    </li>
                    <li class="w-full dark:border-gray-600">
                        <div class="flex items-center p-3">
                            <input id="english-cannot" type="radio" value="cannot" name="english_book_ability"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="english-cannot" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Saya Tidak Bisa
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
            {{-- Active Number Lecturer or Supervisor --}}
            <div class="mb-3">
                <label for="supervisor_contact" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    No. HP Aktif / WA Dosen atau Guru Pembimbing PKL/Magang beserta Nama dan Jabatan 
                    <span class="text-gray-400">(bisa diisi menyusul, jika belum dapat dosen pembimbing)</span>.
                </label>
                <input type="text" id="supervisor_contact" name="supervisor_contact" 
                    class="block w-full p-3 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg shadow-sm 
                        focus:ring-primary-500 focus:border-primary-500 
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white 
                        dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                    placeholder="Contoh: 0812xxxxxxx - Bapak/Ibu Nama (Dosen Pembimbing)">
            </div>

                {{-- What Internship You Interest In --}}
                <div class="mb-5">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Program Magang/PKL apa yang Anda minati?
                </h3>

                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg 
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <!-- Project Manager -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-project-manager" type="radio" value="project-manager" name="internship_interest"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-project-manager"
                            class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Project Manager
                            </label>
                        </div>
                    </li>

                    <!-- Administration -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-administration" type="radio" value="administration" name="internship_interest"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-administration"
                            class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Administration
                            </label>
                        </div>
                    </li>

                    <!-- HR -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-hr" type="radio" value="hr" name="internship_interest"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-hr"
                            class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Human Resources (HR)
                            </label>
                        </div>
                    </li>

                    <!-- UI/UX -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-uiux" type="radio" value="uiux" name="internship_interest"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-uiux"
                            class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                            UI/UX
                            </label>
                        </div>
                    </li>

                    <!-- Programmer -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-programmer" type="radio" value="programmer" name="internship_interest"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-programmer"
                            class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Programmer (Front End / Backend)
                            </label>
                        </div>
                    </li>

                    <!-- Photographer -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-photographer" type="radio" value="photographer" name="internship_interest"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-photographer"
                            class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Photographer
                            </label>
                        </div>
                    </li>

                    <!-- Videographer -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-videographer" type="radio" value="videographer" name="internship_interest"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-videographer" 
                                class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Videographer
                            </label>
                        </div>
                    </li>

                    <!-- Graphic Designer -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-graphic-designer" type="radio" value="graphic-designer" name="internship_interest"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-graphic-designer" 
                                class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Graphic Designer
                            </label>
                        </div>
                    </li>

                    <!-- Social Media Specialist -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-social-media-specialist" type="radio" value="social-media-specialist" name="internship_interest"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-social-media-specialist" 
                                class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Social Media Specialist
                            </label>
                        </div>
                    </li>

                    <!-- Content Writer -->
                    <li class="w-full">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-content-writer" type="radio" value="content-writer" name="internship_interest"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-content-writer" 
                                class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Content Writer
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-content-planner" type="radio" value="content-planner" name="internship_interest"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="radio-content-planner" class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Content Planner
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-marketing-and-sales" type="radio" value="marketing-and-sales" name="internship_interest"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="radio-marketing-and-sales" class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Sales and Marketing
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-public-relation" type="radio" value="public-relation" name="internship_interest"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="radio-public-relation" class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Public Relation / Marcomm
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-digital-marketing" type="radio" value="digital-marketing" name="internship_interest"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="radio-digital-marketing" class="w-full py-3 ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Digital Marketing
                            </label>
                        </div>
                    </li>

                    <!-- TikTok Creator -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-tiktok-creator" type="radio" value="tiktok-creator" name="internship_interest"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 focus:ring-2">
                            <label for="radio-tiktok-creator" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                TikTok Creator
                            </label>
                        </div>
                    </li>

                    <!-- Welding -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-welding" type="radio" value="welding" name="internship_interest"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 focus:ring-2">
                            <label for="radio-welding" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Welding
                            </label>
                        </div>
                    </li>

                    <!-- Customer Service -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-customer-service" type="radio" value="customer-service" name="internship_interest"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 focus:ring-2">
                            <label for="radio-customer-service" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Customer Service
                            </label>
                        </div>
                    </li>

                    <!-- Other -->
                    <li class="w-full rounded-b-lg">
                        <div class="flex flex-col gap-2 px-3 py-3">
                            <div class="flex items-center">
                                <input id="radio-other" type="radio" value="other" name="internship_interest"
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 focus:ring-2">
                                <label for="radio-other" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Other
                                </label>
                            </div>
                            <input type="text" id="radio-other-input" name="internship_interest_other" placeholder="Please specify"
                                class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 
                                    focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
                                    dark:placeholder-gray-400 dark:text-white">
                        </div>
                    </li>
                </ul>
            </div>
            {{-- Internship Schedule --}}
            <div class="mb-6">
                <div class="p-5 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 shadow-sm 
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <h1 class="text-xl font-bold mb-3">📅 Jadwal Magang</h1>
                    <p class="mb-1">Hari magang adalah <strong>Senin - Sabtu</strong>.</p>
                    <p class="mb-1"><strong>Kantor 1 :</strong> Shift Pagi (06.30 - 13.00 WIB) & Shift Siang (13.00 - 21.00 WIB)</p>
                    <p class="mb-1"><strong>Kantor 2 :</strong> Shift Middle (09.00 - 17.00 WIB) / Shift Pagi (06.30 - 13.00 WIB) & Shift Siang (13.00 - 21.00 WIB)</p>
                    <p><strong>Kantor 4 :</strong> Shift Middle (09.00 - 17.00 WIB) / Shift Pagi (06.30 - 13.00 WIB) & Shift Siang (13.00 - 21.00 WIB)</p>
                </div>
            </div>

            {{-- Design Software --}}
            <div class="mb-6">
                <label for="design_software" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">
                    Jika Anda memilih minat program magang di <strong>Disain Grafis</strong> atau <strong>UI/UX Designer</strong>, 
                    software design apa saja yang Anda kuasai?  
                    <span class="font-normal text-gray-500 dark:text-gray-400">(jika minat Anda bukan ini, cukup diisi dengan strip "-")</span>
                </label>
                <input type="text" id="design_software" name="design_software"
                    class="block w-full p-2.5 text-sm rounded-lg border 
                        bg-gray-50 text-gray-900 border-gray-300 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                        dark:bg-gray-700 dark:text-white dark:border-gray-600 
                        dark:placeholder-gray-400" 
                    placeholder="Your Answer" required />
            </div>
            {{-- Video / Digital Marketing --}}
            <div class="mb-6">
                <label for="video_software" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                    Jika Anda memilih minat program magang 
                    <span class="font-semibold">Digital Marketing</span>, materi mana yang ingin Anda praktikan?
                </label>
                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">
                    (jika minat Anda bukan ini, cukup diisi dengan strip "-")
                </p>
                
                <input type="text" id="video_software" name="video_software"
                    class="block w-full p-2.5 text-sm rounded-md border shadow-sm
                        bg-gray-50 text-gray-900 border-gray-300 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                        dark:bg-gray-700 dark:text-white dark:border-gray-600 
                        dark:placeholder-gray-400" 
                    placeholder="Your Answer" required />
            </div>
            {{-- Programming Languages --}}
            <div class="mb-6">
                <label for="programming_languages" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                    Jika kamu memilih minat program magang <span class="font-semibold">Programmer</span>, 
                    bahasa pemrograman apa saja yang sudah kamu kuasai?
                </label>
                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">
                    (jika minat Anda bukan ini, cukup diisi dengan strip "-")
                </p>

                <input type="text" id="programming_languages" name="programming_languages"
                    class="block w-full p-2.5 text-sm rounded-md border shadow-sm
                        bg-gray-50 text-gray-900 border-gray-300 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                        dark:bg-gray-700 dark:text-white dark:border-gray-600 
                        dark:placeholder-gray-400" 
                    placeholder="Your Answer" required />
            </div>
            {{-- digital marketing type --}}
            <div class="mb-3">
                <h3 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Jika Anda memilih minat program magang Digital Marketing, materi mana yang anda ingin praktikan? 
                    <span class="font-normal">(jika minat Anda bukan ini, cukup pilih opsi "Other")</span>
                </h3>

                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg 
                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <!-- Organic -->
                    <li class="w-full">
                        <label for="radio-organic" class="flex items-start gap-3 p-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                            <input id="radio-organic" type="radio" value="organic" name="digital_marketing_type"
                                class="mt-0.5 w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-500 
                                    focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                            <span class="text-sm">
                                Digital Marketing Organic
                                <span class="block text-xs text-gray-500 dark:text-gray-400">
                                    (Free tanpa dana untuk beriklan)
                                </span>
                            </span>
                        </label>
                    </li>

                    <!-- Ads -->
                    <li class="w-full">
                        <label for="radio-ads" class="flex items-start gap-3 p-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                            <input id="radio-ads" type="radio" value="ads" name="digital_marketing_type"
                                class="mt-0.5 w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-500 
                                    focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                            <span class="text-sm">
                                Digital Marketing Ads (Fb Ads / Ig Ads) 
                                <span class="block text-xs text-gray-500 dark:text-gray-400">
                                    (Harus menyiapkan dana min. 30K/day selama iklan berjalan)
                                </span>
                            </span>
                        </label>
                    </li>

                    <!-- Other -->
                    <li class="w-full">
                        <div class="flex flex-col gap-2 p-3">
                            <label for="radio-other-marketing-2" class="flex items-center gap-2 cursor-pointer">
                                <input id="radio-other-marketing-2" type="radio" value="other2" name="digital_marketing_type"
                                    class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-500 
                                        focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                                <span class="text-sm">Other</span>
                            </label>
                            <input type="text" id="radio-other-marketing-2-input" name="digital_marketing_type_other" 
                                placeholder="Please specify"
                                class="block w-full p-2.5 text-sm rounded-md border shadow-sm
                                    bg-gray-50 text-gray-900 border-gray-300 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                    dark:bg-gray-700 dark:text-white dark:border-gray-600 
                                    dark:placeholder-gray-400" />
                        </div>
                    </li>
                </ul>
            </div>
            {{-- Laptop Equipment --}}
            <div class="mb-6">
                <h3 class="block mb-3 text-sm font-semibold text-gray-900 dark:text-white">
                    Apakah Anda ada alat kerja sendiri (LAPTOP) yang bisa dipakai selama Magang/PKL?
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg 
                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-yes-laptop" type="radio" value="yes-laptop" name="laptop_equipment"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="radio-yes-laptop" class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                YA ADA
                            </label>
                        </div>
                    </li>
                    <li class="w-full">
                        <div class="flex items-center px-3 py-2">
                            <input id="radio-no-laptop" type="radio" value="no-laptop" name="laptop_equipment"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="radio-no-laptop" class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                TIDAK ADA
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Owned Tools --}}
            <div class="mb-6">                
                <h3 class="block mb-3 text-sm font-semibold text-gray-900 dark:text-white">
                    Jika YA, alat apa yang Anda miliki, yang bisa Anda bawa selama Magang/PKL?
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg 
                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-3 py-2">
                            <input id="tool-corel-photoshop" type="checkbox" value="ownedtools1" name="owned_tools"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="tool-corel-photoshop" class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Laptop yang sudah terinstal Corel & Photoshop
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-3 py-2">
                            <input id="tool-adobe-video" type="checkbox" value="ownedtools2" name="owned_tools"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="tool-adobe-video" class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Laptop yang sudah terinstal Adobe Premiere/Final Cut/After Effect
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-3 py-2">
                            <input id="tool-dslr" type="checkbox" value="ownedtools3" name="owned_tools"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="tool-dslr" class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Kamera DSLR
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-3 py-2">
                            <input id="tool-laptop-netbook" type="checkbox" value="ownedtools4" name="owned_tools"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                            <label for="tool-laptop-netbook" class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Laptop / Netbook
                            </label>
                        </div>
                    </li>

                    <li class="w-full">
                        <div class="flex flex-col gap-2 px-3 py-3">
                            <div class="flex items-center">
                                <input id="tool-other-checkbox" type="checkbox" value="ownedtoolsother" name="owned_tools_other"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 
                                        dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600">
                                <label for="tool-other-checkbox" class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Lainnya
                                </label>
                            </div>
                            <input type="text" id="tool-other-checkbox-input" name="owned_tools" placeholder="Sebutkan alat lainnya"
                                class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                    </li>
                </ul>
            </div>
            {{-- Start Date and End Date --}}
            <div class="mb-6">
            <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">
                Kapan Anda berencana mulai Magang/PKL?
                <span class="block text-xs font-normal text-gray-500 dark:text-gray-400 mt-1">
                (Tulis lengkap tanggal, bulan, tahun) beserta durasi magang
                </span>
            </label>

            <div id="date-range-picker" date-rangepicker class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Start Date -->
                <div class="relative w-full sm:max-w-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input id="start_date" datepicker datepicker-autohide type="text" name="start_date"
                    value="{{ request('start_date') }}"
                    class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 placeholder-gray-400"
                    placeholder="Tanggal mulai">
                </div>

                <span class="text-gray-700 dark:text-gray-300">s/d</span>

                <!-- End Date -->
                <div class="relative w-full sm:max-w-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input id="end_date" datepicker datepicker-autohide type="text" name="end_date"
                    value="{{ request('end_date') }}"
                    class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 placeholder-gray-400"
                    placeholder="Tanggal selesai">
                </div>
            </div>
            </div>

            {{-- Internship Info Source --}}
            <div class="mb-3">                
                <h3 class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">
                    Anda tahu info magang ini darimana?
                </h3>

                <ul class="w-full text-sm font-medium text-gray-900 dark:text-white 
                    bg-white border border-gray-200 rounded-lg 
                    dark:bg-gray-700 dark:border-gray-600">
                    
                    <!-- Website -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <label for="website-checkbox" class="flex items-center gap-3 p-3 cursor-pointer">
                            <input id="website-checkbox" type="checkbox" value="website" name="internship_info_sources"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded dark:border-gray-500 
                                focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                            <span>Website</span>
                        </label>
                    </li>

                    <!-- Instagram -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <label for="instagram-checkbox" class="flex items-center gap-3 p-3 cursor-pointer">
                            <input id="instagram-checkbox" type="checkbox" value="instagram" name="internship_info_sources"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded dark:border-gray-500 
                                focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                            <span>Instagram</span>
                        </label>
                    </li>

                    <!-- Twitter -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <label for="twitter-checkbox" class="flex items-center gap-3 p-3 cursor-pointer">
                            <input id="twitter-checkbox" type="checkbox" value="twitter" name="internship_info_sources"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded dark:border-gray-500 
                                focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                            <span>Twitter</span>
                        </label>
                    </li>

                    <!-- Glints -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <label for="glints-checkbox" class="flex items-center gap-3 p-3 cursor-pointer">
                            <input id="glints-checkbox" type="checkbox" value="glints" name="internship_info_sources"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded dark:border-gray-500 
                                focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                            <span>Glints</span>
                        </label>
                    </li>

                    <!-- Youtube -->
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <label for="youtube-checkbox" class="flex items-center gap-3 p-3 cursor-pointer">
                            <input id="youtube-checkbox" type="checkbox" value="youtube" name="internship_info_sources"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded dark:border-gray-500 
                                focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                            <span>Youtube</span>
                        </label>
                    </li>

                    <!-- Other -->
                    <li class="w-full">
                        <div class="flex flex-col gap-2 p-3">
                            <label for="tool-other-checkbox-2" class="flex items-center gap-3 cursor-pointer">
                                <input id="tool-other-checkbox-2" type="checkbox" value="other" name="internship_info_other"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded dark:border-gray-500 
                                    focus:ring-2 focus:ring-blue-600 dark:bg-gray-600">
                                <span>Other</span>
                            </label>
                            <input type="text" id="tool-other-checkbox-input-2" name="internship_info_other_text" 
                                placeholder="Please specify"
                                class="block w-full p-2 text-xs rounded-lg border border-gray-300 
                                    bg-gray-50 text-gray-900 focus:ring-blue-500 focus:border-blue-500 
                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                                    dark:text-white">
                        </div>
                    </li>
                </ul>
            </div>
            <div class="space-y-8">
                <!-- Upload CV, KTP, Portofolio -->
                <div>
                    <label for="file-input-1" class="block mb-3 text-sm font-semibold">
                        Upload <span class="font-bold">CV, Scan KTP/KTM, Portofolio</span>
                        <span class="text-gray-400 text-xs font-normal">(Format PDF)</span>
                    </label>
                    <input id="file-input-1" type="file" name="cv_ktp_portofolio_pdf"
                        class="block w-full text-sm text-gray-300 border border-gray-600 rounded-lg cursor-pointer 
                             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition p-2" />
                </div>

                <!-- Upload Portofolio Visual -->
                <div>
                    <label for="file-input-2" class="block mb-3 text-sm font-semibold">
                        Upload <span class="font-bold">Portofolio Visual</span>
                    </label>
                    <p class="text-gray-400 text-xs mb-2">
                        (JPG untuk desainer grafis atau link Youtube untuk videografer)
                    </p>
                    <input id="file-input-2" type="file" name="portofolio_visual"
                        class="block w-full text-sm text-gray-300 border border-gray-600 rounded-lg cursor-pointer 
                             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition p-2" />
                </div>

                <!-- Current Activities -->
                <div>
                    <label for="current-activities" class="block mb-3 text-sm font-semibold">
                        Kegiatan Anda saat ini selain magang/PKL?
                    </label>
                    <p class="text-gray-400 text-xs mb-2">(Jika ada, mohon sebutkan)</p>
                    <input type="text" id="current-activities" name="current_activities"
                        placeholder="Tuliskan jawaban Anda..."
                        class="w-full p-3 text-sm placeholder-gray-400 
                             border border-gray-600 rounded-lg 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                </div>

                <!-- Boarding Info -->
                <div>
                    <label class="block mb-3 text-sm font-semibold">
                        Apakah Anda membutuhkan info <span class="font-bold">kos/kontrakan</span> dekat kantor?
                    </label>
                    <div class="border border-gray-600 rounded-lg divide-y divide-gray-600">
                        <!-- YES -->
                        <label for="boarding-info-yes" class="flex items-center gap-3 p-3 cursor-pointer hover:bg-gray-300 transition">
                            <input id="boarding-info-yes" type="radio" value="yes" name="boarding_info"
                                class="w-4 h-4 text-blue-600  border-gray-500 focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm">YA</span>
                        </label>
                        <!-- NO -->
                        <label for="boarding-info-no" class="flex items-center gap-3 p-3 cursor-pointer hover:bg-gray-300 transition">
                            <input id="boarding-info-no" type="radio" value="no" name="boarding_info"
                                class="w-4 h-4 text-blue-600 bg border-gray-500 focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm">TIDAK</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Apakah Anda sudah <strong>berkeluarga</strong>?
                </h3>
                <ul
                    class="w-full text-sm font-medium 
                        text-gray-900 dark:text-gray-300 
                        bg-white dark:bg-gray-700 
                        border border-black dark:border-white 
                        rounded-lg overflow-hidden"
                >
                    <!-- YA -->
                    <li class="border-b border-black dark:border-white">
                        <label for="family-status-yes"
                            class="flex items-center ps-3 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <input id="family-status-yes" type="radio" value="yes" name="family_status"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 
                                    dark:bg-gray-600 dark:border-gray-500 
                                    focus:ring-2 focus:ring-blue-500" />
                            <span class="ms-2">YA</span>
                        </label>
                    </li>

                    <!-- TIDAK -->
                    <li>
                        <label for="family-status-no"
                            class="flex items-center ps-3 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <input id="family-status-no" type="radio" value="no" name="family_status"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 
                                    dark:bg-gray-600 dark:border-gray-500 
                                    focus:ring-2 focus:ring-blue-500" />
                            <span class="ms-2">TIDAK</span>
                        </label>
                    </li>
                </ul>
            </div>
            <!-- Kontak Wali/Ortu -->
            <div class="mb-5">
                <label for="parent-wa-contact" 
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    No. HP Aktif (WA) Wali / Ortu Peserta Magang
                </label>
                <input type="text" id="parent-wa-contact" name="parent_wa_contact"
                    class="block w-full p-3 text-sm 
                        text-gray-900 dark:text-white 
                        bg-gray-50 dark:bg-gray-700 
                        border border-black dark:border-white 
                        rounded-lg shadow-sm 
                        placeholder-gray-400 
                        focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="0812 1111 8888 (Bapak Adi Pangestu)">
            </div>

            <!-- Sosial Media Instagram -->
            <div class="mb-5">
                <label for="social-media-link" 
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Sosial Media (Instagram) yang Dimiliki
                </label>
                <input type="text" id="social-media-link" name="social_media_instagram"
                    class="block w-full p-3 text-sm 
                        text-gray-900 dark:text-white 
                        bg-gray-50 dark:bg-gray-700 
                        border border-black dark:border-white 
                        rounded-lg shadow-sm 
                        placeholder-gray-400 
                        focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Instagram : (username / link profil)">
            </div>

            <!-- Info Box -->
            <div class="mb-5">
                <div class="p-5 w-full text-sm leading-relaxed 
                            text-gray-900 dark:text-white 
                            bg-gray-50 dark:bg-gray-700 
                            border border-black dark:border-white 
                            rounded-lg shadow-md">
                    <h1 class="text-base md:text-lg font-medium">
                        Program Magang ini bersifat <span class="font-semibold text-black-500">unpaid / tidak bergaji</span>. 
                        Jika Anda setuju, silakan <strong>SUBMIT</strong> dan 
                        <strong>KONFIRMASI</strong> ke WA Admin 
                        <span class="font-semibold text-black-500">0895 2900 2944</span> 
                        dengan pesan: 
                        <span class="italic text-black-500">"SAYA SUDAH ISI FORM"</span>.  
                        <br><br>Terima kasih 🙏
                    </h1>
                </div>
            </div>

            <button type="submit"
                    class="py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Kirim Formulir
            </button>
        </form>
    </section>
</div>
</body>
</html>

@extends('layouts.dashboard')
@section('content')
<section class="bg-white dark:bg-gray-900">
<form action="{{ route('internship-registration.store') }}" method="POST" enctype="multipart/form-data">
@csrf
  <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900 dark:text-white">Form Pendaftaran Magang/PKL</h2>
      <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 dark:text-gray-400 sm:text-xl">Silahkan isi form pendaftaran ini dengan lengkap untuk memudahkan kami dalam memproses. Terimakasih.</p>
      <form action="#" class="space-y-8">

        {{-- Full Name --}}
          <div class="mb-3">
              <label for="fullname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama Lengkap</label>
              <input type="text" id="fullname" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="e.g. Jawara Handoko" required>
          </div>

          {{-- Born Date --}}
          <div class="mb-3">
              <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tahun Lahir</label>
              <input datepicker datepicker-autohide type="text" id="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Select date" required>
          </div>

          {{-- Student ID --}}
          <div class="mb-3">
              <label for="student-id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nomor Induk Mahasiswa</label>
              <input type="text" id="student-id" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Your student ID" required>
          </div>

          {{-- Email --}}
          <div class="mb-3">
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email</label>
              <input type="email" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="surya@example.com" required>
          </div>

          {{-- Gender --}}
          <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Jenis Kelamin</h3>
                <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="list-radio-male-id" type="radio" value="male" name="gender" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="list-radio-male-id" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Laki-laki</label>
                    </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="list-radio-female-id" type="radio" value="female" name="gender" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="list-radio-female-id" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Perempuan</label>
                    </div>
                    </li>
                </ul>
            </div>

            {{-- Active phone number or WhatsApp number --}}
            <div class="mb-3">
                <label for="phone-number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">No. HP Aktif yang bisa dihubungi dan chat WA</label>
                <input type="text" id="phone-number" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Your answer" required>
            </div>

            {{-- Institution Name --}}
            <div class="mb-3">
                <label for="institution-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Asal Sekolah/Kampus</label>
                <input type="text" id="institution-name" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Your answer" required>
            </div>

            {{-- Study Program --}}
            <div class="mb-3">
                <label for="study-program" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Program Studi</label>
                <input type="text" id="study-program" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Your answer" required>
            </div>

            {{-- Faculty --}}
            <div class="mb-3">
                <label for="faculty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Fakultas</label>
                <input type="text" id="faculty" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Your answer" required>
            </div>

            {{-- Current City/Region --}}
            <div class="mb-3">
                <label for="current-city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama Kota/Daerah tempat tinggal anda saat ini</label>
                <input type="text" id="current-city" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Your answer" required>
            </div>

            {{-- Do Internship Here --}}
            <div class="mb-3">
                <label for="do-internship-here" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mengapa Anda ingin Magang/PKL disini?</label>
                <input type="text" id="do-internship-here" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Your answer" required>
            </div>

            {{-- Type Of Internship --}}
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Jenis Magang apa yang Anda pilih?
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-self-internship-id" type="radio" value="self" name="internship-type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-self-internship-id" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Magang Mandiri (magang atas inisatif diri sendiri, bukan kewajiban dari kampus)
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-university-required-id" type="radio" value="university" name="internship-type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-university-required-id" class="w-full py-5 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Magang Kampus / Reguler (magang karena kewajiban dari kampus)
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- WORK FROM OFFICE --}}
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Saya Siap dengan Sistem Magang ini:
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-wfo" type="radio" value="wfo" name="internship-arrangement" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-wfo" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                WFO ( Work From Office )
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Your Current Status --}}
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Status Anda saat ini:
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-school-or-college" type="radio" value="school-or-college" name="current-status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-school-or-college" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Masih sekolah/masih kuliah
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-unemployed" type="radio" value="unemployed" name="current-status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-unemployed" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Sudah lulus sekolah/kuliah dan belum bekerja
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-employed" type="radio" value="employed" name="current-status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-employed" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Sudah lulus dan sedang bekerja
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Read English Book --}}
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Apakah Anda bisa membaca buku berbahasa Inggris kah?
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-ican" type="radio" value="ican" name="english-book" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-ican" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Saya Bisa
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-limited-ability" type="radio" value="limited-ability" name="english-book" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-limited-ability" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Saya Kurang Bisa (bisa sedikit-sedikit)
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="list-radio-cannot" type="radio" value="cannot" name="english-book" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="list-radio-cannot" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Saya Tidak bisa
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Active Number Lecturer or Supervisor --}}
            <div class="mb-3">
                <label for="do-internship-here" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">No. HP Aktif / WA Dosen atau Guru Pembimbing PKL/Magang beserta Nama dan Jabatan (bisa diisi menyusul, jika belum dapat dosen pembimbing).</label>
                <input type="text" id="do-internship-here" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Your answer" required>
            </div>

            {{-- What Internship You Interest In --}}
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Program Magang/PKL apa yang Anda minati?
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <!-- Loop 19 radio buttons -->
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-project-manager" type="radio" value="project-manager" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-project-manager" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Project Manager
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-administration" type="radio" value="administration" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-administration" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Administration
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-hr" type="radio" value="hr" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-hr" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Human Resources ( HR )
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-uiux" type="radio" value="uiux" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-uiux" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                UI/UX
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-programmer" type="radio" value="programmer" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-programmer" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Programmer ( Front End / Backend )
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-photographer" type="radio" value="photographer" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-photographer" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Photographer
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-videographer" type="radio" value="videographer" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-videographer" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Videographer
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-graphic-designer" type="radio" value="graphic-designer" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-graphic-designer" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Graphic Designer
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-social-media-specialist" type="radio" value="social-media-specialist" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-social-media-specialist" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Social Media Specialist
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-content-writer" type="radio" value="content-writer" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-content-writer" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Content Writer
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-content-planner" type="radio" value="content-planner" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-content-planner" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Content Planner
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-marketing-and-sales" type="radio" value="marketing-and-sales" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-marketing-and-sales" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Sales and Marketing
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-public-relation" type="radio" value="public-relation" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-public-relation" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Public Relation / Marcomm
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-digital-marketing" type="radio" value="digital-marketing" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-digital-marketing" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Digital Marketing
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-tiktok-creator" type="radio" value="tiktok-creator" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-tiktok-creator" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Tiktok Creator
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-welding" type="radio" value="welding" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-welding" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Welding
                            </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-customer-service" type="radio" value="customer-service" name="internship-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                            <label for="radio-customer-service" class="w-full py-3 ms-6 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Customer Service
                            </label>
                        </div>
                    </li>
                    <li class="w-full rounded-b-lg">
                        <div class="flex flex-col gap-2 ps-3 py-3">
                            <div class="flex items-center">
                                <input id="radio-other-marketing-1" type="radio" value="other" name="digital-marketing-type"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                        dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                                <label for="radio-other-marketing-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Other
                                </label>
                            </div>
                            <input type="text" id="radio-other-marketing-1-input" name="other-material" placeholder="Please specify"
                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs 
                                    focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
                                    dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Internship Schedule --}}
            <div class="mb-3">
                <div class="p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:shadow-sm-light">
                    <h1 class="text-2xl">
                        <strong>Jadwal Magang</strong>
                    </h1>
                    <p>Hari magang adalah <strong>Senin - Sabtu.</strong>.</p>
                    <p><strong>Kantor 1 :</strong> Shift pagi (06.30 - 13.00 WIB) dan Shift siang (13.00 - 21.00 WIB)</p>
                    <p><strong>Kantor 2 :</strong> Shift Middle (09.00 - 17.00 WIB)/ Shift pagi (06.30 - 13.00 WIB) dan Shift siang (13.00 - 21.00 WIB)</p>
                    <p><strong>Kantor 4 :</strong> Shift Middle (09.00 - 17.00 WIB)/ Shift pagi (06.30 - 13.00 WIB) dan Shift siang (13.00 - 21.00 WIB)</p>
                </div>
            </div>

            <div class="mb-3">
                <label for="design_software" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Jika Anda memilih minat program magang di Disain Grafis atau UI/UX Designer, software design apa saja yang Anda kuasai ?  
                    <span class="font-normal">(jika minat Anda bukan ini, cukup diisi dg strip "-")</span>
                </label>
                <input type="text" id="design_software" name="design_software"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="Your Answer" required />
            </div>

            <div class="mb-3">
                <label for="video_software" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Jika Anda memilih minat program magang Digital Marketing, materi mana yang anda ingin praktikan? 
                    <span class="font-normal">(jika minat Anda bukan ini, cukup pilih opsi "other")</span>
                </label>
                <input type="text" id="video_software" name="video_software"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="Your Answer" required />
            </div>

            <div class="mb-3">
                <label for="programming_languages" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    If you choose the Programmer internship program interest, what programming languages ​​have you mastered? 
                    <span class="font-normal">(if your interest is not this, just fill in with a dash "-")</span>
                </label>
                <input type="text" id="programming_languages" name="programming_languages"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="Your Answer" required />
            </div>

            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Jika Anda memilih minat program magang Digital Marketing, materi mana yang anda ingin praktikan? 
                    <span class="font-normal">(jika minat Anda bukan ini, cukup pilih opsi "other")</span>
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-organic" type="radio" value="organic" name="digital-marketing-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 
                                    focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="radio-organic" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Digital Marketing Organic. Nb : Free tanpa dana untuk beriklan.
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-ads" type="radio" value="ads" name="digital-marketing-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 
                                    focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="radio-ads" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Digital Marketing Ads (Fb Ads / Ig Ads). Nb : Harus menyiapkan dana utk belajar beriklan dengan Ads min. 30 K/day selama iklan berjalan.
                            </label>
                        </div>
                    </li>
                    <li class="w-full rounded-b-lg">
                        <div class="flex flex-col gap-2 ps-3 py-3">
                            <div class="flex items-center">
                                <input id="radio-other-marketing-2" type="radio" value="other2" name="digital-marketing-type"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                        dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                                <label for="radio-other-marketing-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Other
                                </label>
                            </div>
                            <input type="text" id="radio-other-marketing-2-input" name="other-material2" placeholder="Please specify"
                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs 
                                    focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
                                    dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </li>
                </ul>
            </div>
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Apakah Anda ada alat kerja sendiri (LAPTOP) yang bisa dipakai selama Magang/PKL?
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-yes-laptop" type="radio" value="yes-laptop" name="laptop-equipment"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 
                                    focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="radio-yes-laptop" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                YA ADA
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="radio-no-laptop" type="radio" value="no-laptop" name="laptop-equipment"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 
                                    focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="radio-no-laptop" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                TIDAK ADA
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="mb-3">                
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Jika YA, alat apa yang Anda miliki, yang bisa Anda bawa selama Magang/PKL?
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="tool-corel-photoshop" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="tool-corel-photoshop" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        Laptop yang sudah terinstal Corel dan Photoshop
                        </label>
                    </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="tool-adobe-video" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="tool-adobe-video" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        Laptop yang sudah terinstal Adobe Premiere Pro/Final Cut Pro/Adobe After Effect
                        </label>
                    </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="tool-dslr" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="tool-dslr" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        Kamera DSLR
                        </label>
                    </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="tool-laptop-netbook" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="tool-laptop-netbook" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        Laptop/Netbook
                        </label>
                    </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex flex-col gap-2 ps-3 py-3">
                            <div class="flex items-center">
                                <input id="tool-other-checkbox" type="checkbox" value="other" name="digital-tools"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                                <label for="tool-other-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Other
                                </label>
                            </div>
                            <input type="text" id="tool-other-checkbox-input" name="other-material-tools" placeholder="Please specify"
                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs 
                                    focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
                                    dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </li>
                </ul>
            </div>
            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Kapan Anda berencana mulai Magang/PKL? (tulis lengkap tanggal, bulan, tahun) beserta durasi magang
                </h3>
                <div id="date-range-picker" date-rangepicker class="flex items-center">
                    <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input id="datepicker-autohide" datepicker datepicker-autohide type="text" name="start_date" value="{{ request('start_date') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-customprimary-500 focus:border-customprimary-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-customprimary-500 dark:focus:border-customprimary-500"
                                placeholder="Select date">
                        </div>
                        <div class="dark:text-white px-2">s/d</div>
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input id="datepicker-autohide" datepicker datepicker-autohide type="text" name="end_date" value="{{ request('end_date') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-customprimary-500 focus:border-customprimary-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-customprimary-500 dark:focus:border-customprimary-500"
                                placeholder="Select date">
                        </div>
                        <div class="relative max-w-sm hidden">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input id="datepicker-autohide" datepicker datepicker-autohide type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-customprimary-500 focus:border-customprimary-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-customprimary-500 dark:focus:border-customprimary-500"
                                placeholder="Select date">
                        </div>
                        <div class="relative max-w-sm hidden">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                        <input id="datepicker-autohide" datepicker datepicker-autohide type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                        </div>
                </div>
            </div>

            <div class="mb-3">                
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anda tahu info magang ini darimana?</h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="website-checkbox" type="checkbox" value="website" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="website-checkbox" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Website</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="instagram-checkbox" type="checkbox" value="instagram" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="instagram-checkbox" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Instagram</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="twitter-checkbox" type="checkbox" value="twitter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="twitter-checkbox" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Twitter</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="glints-checkbox" type="checkbox" value="glints" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="glints-checkbox" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Glints</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="laravel-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="laravel-checkbox" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Youtube</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex flex-col gap-2 ps-3 py-3">
                            <div class="flex items-center">
                                <input id="tool-other-checkbox-2" type="checkbox" value="other" name="digital-tools-2"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 focus:ring-2">
                                <label for="tool-other-checkbox-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Other
                                </label>
                            </div>
                            <input type="text" id="tool-other-checkbox-input-2" name="other-material-tools-2" placeholder="Please specify"
                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs 
                                    focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
                                    dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </li>
                </ul>
            </div>
            
            <div class="mb-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file-input-1">Silakan upload : CV, Scan KTP/KTM, Portofolio disini. Format pdf.</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file-input-1" type="file">
            </div>

            <div class="mb-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input-2">Silakan upload : Portofolio (berupa gambar jpg bagi disainer grafis atau link youtube bagi videografer)</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file-input-2" type="file">
            </div>

            <div class="mb-3">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kegiatan Anda saat ini selain magang/pkl ada kah? Jika ada, mohon sebutkan apa saja :</label>
                <input type="text" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Answer">
            </div>

            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Apakah Anda membutuhkan info <strong>𝗸𝗼𝘀𝘁/𝗸𝗼𝗻𝘁𝗿𝗮𝗸𝗮𝗻</strong> dekat kantor?

                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="boarding-info-yes" type="radio" value="yes" name="boarding-info"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 
                                    focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="boarding-info-yes" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                YES
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="boarding-info-no" type="radio" value="no" name="boarding-info"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 
                                    focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="boarding-info-no" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                NO
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="mb-3">
                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Apakah Anda sudah <strong>berkeluarga</strong>? <span class="text-red-600">*</span>
                </h3>
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    
                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3 py-3">
                            <input id="family-status-yes" type="radio" value="yes" name="family-status"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 
                                    focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="family-status-yes" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                YA
                            </label>
                        </div>
                    </li>

                    <li class="w-full border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center ps-3 py-3">
                            <input id="family-status-no" type="radio" value="no" name="family-status"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                    dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 
                                    focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="family-status-no" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                TIDAK
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="mb-3">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No. HP Aktif (WA) Wali/ Ortu peserta magang</label>
                <input type="text" id="parent-wa-contact" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="0812 1111 8888 (Bapak Adi Pangestu)">
            </div>

            <div class="mb-3">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sosial Media (Intagram) yang dimiliki</label>
                <input type="text" id="social-media-link" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Instagram : (username-profile link)">
            </div>

            <div>
                <div class="p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:shadow-sm-light">
                    <h1 class="text-lg">
                        Program Magang ini bersifat unpaid/tidak bergaji. Jika Anda setuju maka silakan SUBMIT dan KONFIRMASI ke WA Admin 0895 2900 2944 bahwa "SAYA SUDAH ISI FORM". Terimakasih
                    </h1>
                </div>
            </div>
            
          <button type="submit" class="py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-primary-700 sm:w-fit hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Submit</button>
      </form>
  </div>
  <script src="https://unpkg.com/flowbite@1.6.5/dist/datepicker.js"></script>

</section>
@endsection

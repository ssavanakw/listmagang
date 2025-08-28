<!-- resources/views/internship-registration/form.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <title>Form Pendaftaran Magang/PKL</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  
</head>

<body class="bg-emerald-400/20 dark:bg-emerald-950 min-h-screen flex items-start sm:items-center justify-center p-4">
  <div class="w-full max-w-2xl">
    <!-- Header card -->
    <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur rounded-2xl shadow-xl ring-1 ring-zinc-200 dark:ring-zinc-800 overflow-hidden">
      
      <section class="py-8 lg:py-10 px-5 sm:px-8">
        <h2 class="mb-3 text-2xl sm:text-3xl font-extrabold text-center text-zinc-900 dark:text-zinc-50">
          Form Pendaftaran Magang/PKL
        </h2>
        <p class="mb-8 text-center text-sm sm:text-base text-zinc-600 dark:text-zinc-400">
          Silakan isi form pendaftaran ini dengan lengkap untuk memudahkan kami dalam memproses. Terima kasih.
        </p>

        <form action="{{ route('internship.store') }}" method="POST" enctype="multipart/form-data" class="space-y-7">
          @csrf

          {{-- Utility classes (consisten) --}}
          @php
            $label = 'block mb-2 text-sm font-medium text-zinc-800 dark:text-zinc-200';
            $help  = 'mt-1 text-xs text-zinc-500 dark:text-zinc-400';
            $input = 'block w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-950
                      text-zinc-900 dark:text-zinc-100 placeholder-zinc-400
                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                      px-3 py-2.5 shadow-sm';
            $group = 'bg-white dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-700 rounded-lg';
            $radio = 'w-4 h-4 text-emerald-600 border-zinc-300 dark:border-zinc-600
                      focus:ring-2 focus:ring-emerald-500 dark:bg-zinc-800';
            $check = $radio;
            $item  = 'flex items-center gap-3 px-3 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition';
          @endphp

          <!-- fullname -->
          <div>
            <label for="fullname" class="{{ $label }}">Nama Lengkap</label>
            <input type="text" id="fullname" name="fullname" required placeholder="Nama lengkap Anda" class="{{ $input }}" />
          </div>

          <!-- born_date -->
          <div>
            <label for="born_date" class="{{ $label }}">Tahun Lahir</label>
            <input datepicker datepicker-autohide type="text" id="born_date" name="born_date" placeholder="Pilih tanggal" required class="{{ $input }}" />
          </div>

          {{-- Student ID --}}
          <div>
            <label for="student_id" class="{{ $label }}">Nomor Induk Mahasiswa / Siswa</label>
            <input type="text" id="student_id" name="student_id" placeholder="Contoh: 21.11.1234" required class="{{ $input }}" />
          </div>

          {{-- Email --}}
          <div>
            <label for="email" class="{{ $label }}">Email</label>
            <input type="email" id="email" name="email" placeholder="surya@example.com" required class="{{ $input }}" />
          </div>

          {{-- Gender --}}
          <div>
            <h3 class="{{ $label }}">Jenis Kelamin</h3>
            <ul class="{{ $group }}">
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="list-radio-male-id" class="{{ $item }}">
                  <input id="list-radio-male-id" type="radio" value="male" name="gender" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Laki-laki</span>
                </label>
              </li>
              <li>
                <label for="list-radio-female-id" class="{{ $item }}">
                  <input id="list-radio-female-id" type="radio" value="female" name="gender" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Perempuan</span>
                </label>
              </li>
            </ul>
          </div>

          {{-- Phone --}}
          <div>
            <label for="phone_number" class="{{ $label }}">No. HP Aktif (WhatsApp)</label>
            <input type="text" id="phone_number" name="phone_number" placeholder="08xx xxxx xxxx" required class="{{ $input }}" />
          </div>

          {{-- Institution --}}
          <div>
            <label for="institution_name" class="{{ $label }}">Asal Sekolah/Kampus</label>
            <input type="text" id="institution_name" name="institution_name" placeholder="Nama institusi" required class="{{ $input }}" />
          </div>

          {{-- Study Program --}}
          <div>
            <label for="study_program" class="{{ $label }}">Program Studi</label>
            <input type="text" id="study_program" name="study_program" placeholder="Program studi" required class="{{ $input }}" />
          </div>

          {{-- Faculty --}}
          <div>
            <label for="faculty" class="{{ $label }}">Fakultas</label>
            <input type="text" id="faculty" name="faculty" placeholder="Fakultas" required class="{{ $input }}" />
          </div>

          {{-- Current City --}}
          <div>
            <label for="current_city" class="{{ $label }}">Kota/Daerah tempat tinggal saat ini</label>
            <input type="text" id="current_city" name="current_city" placeholder="Kota/daerah" required class="{{ $input }}" />
          </div>

          {{-- Reason --}}
          <div>
            <label for="internship_reason" class="{{ $label }}">Mengapa Anda ingin Magang/PKL di sini?</label>
            <input type="text" id="internship_reason" name="internship_reason" placeholder="Alasan Anda" required class="{{ $input }}" />
          </div>

          {{-- Internship Type --}}
          <div>
            <h3 class="{{ $label }}">Jenis Magang yang dipilih</h3>
            <ul class="{{ $group }}">
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="list-radio-self-internship-id" class="{{ $item }}">
                  <input id="list-radio-self-internship-id" type="radio" value="self" name="internship_type" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Magang Mandiri</span>
                </label>
              </li>
              <li>
                <label for="list-radio-university-required-id" class="{{ $item }}">
                  <input id="list-radio-university-required-id" type="radio" value="university" name="internship_type" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Magang Kampus / Reguler</span>
                </label>
              </li>
            </ul>
          </div>

          {{-- Arrangement (WFO) --}}
          <div>
            <h3 class="{{ $label }}">Sistem Magang</h3>
            <ul class="{{ $group }}">
              <li>
                <label for="list-radio-wfo" class="{{ $item }}">
                  <input id="list-radio-wfo" type="radio" value="wfo" name="internship_arrangement" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">WFO (Work From Office)</span>
                </label>
              </li>
            </ul>
          </div>

          {{-- Current Status --}}
          <div>
            <h3 class="{{ $label }}">Status Anda saat ini</h3>
            <ul class="{{ $group }}">
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="status-school-or-college" class="{{ $item }}">
                  <input id="status-school-or-college" type="radio" value="school-or-college" name="current_status" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Masih sekolah/kuliah</span>
                </label>
              </li>
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="status-unemployed" class="{{ $item }}">
                  <input id="status-unemployed" type="radio" value="unemployed" name="current_status" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Lulus & belum bekerja</span>
                </label>
              </li>
              <li>
                <label for="status-employed" class="{{ $item }}">
                  <input id="status-employed" type="radio" value="employed" name="current_status" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Lulus & sudah bekerja</span>
                </label>
              </li>
            </ul>
          </div>

          {{-- Read English Book --}}
          <div>
            <h3 class="{{ $label }}">Kemampuan membaca buku berbahasa Inggris</h3>
            <ul class="{{ $group }}">
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="english-ican" class="{{ $item }}">
                  <input id="english-ican" type="radio" value="ican" name="english_book_ability" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Saya bisa</span>
                </label>
              </li>
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="english-limited" class="{{ $item }}">
                  <input id="english-limited" type="radio" value="limited-ability" name="english_book_ability" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Kurang bisa</span>
                </label>
              </li>
              <li>
                <label for="english-cannot" class="{{ $item }}">
                  <input id="english-cannot" type="radio" value="cannot" name="english_book_ability" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">Tidak bisa</span>
                </label>
              </li>
            </ul>
          </div>

          {{-- Supervisor Contact --}}
          <div>
            <label for="supervisor_contact" class="{{ $label }}">
              No. HP/WA Dosen atau Guru Pembimbing (bisa menyusul)
            </label>
            <input type="text" id="supervisor_contact" name="supervisor_contact" placeholder="0812xxxxxxx - Nama (Dosen/Guru)"
                   class="{{ $input }}" />
          </div>

          {{-- Interest --}}
          <div>
            <h3 class="{{ $label }}">Program Magang/PKL yang diminati</h3>
            <ul class="{{ $group }}">
              @foreach ([
                ['project-manager','Project Manager'],
                ['administration','Administration'],
                ['hr','Human Resources (HR)'],
                ['uiux','UI/UX'],
                ['programmer','Programmer (Front End / Backend)'],
                ['photographer','Photographer'],
                ['videographer','Videographer'],
                ['graphic-designer','Graphic Designer'],
                ['social-media-specialist','Social Media Specialist'],
                ['content-writer','Content Writer'],
                ['content-planner','Content Planner'],
                ['marketing-and-sales','Sales and Marketing'],
                ['public-relation','Public Relation / Marcomm'],
                ['digital-marketing','Digital Marketing'],
                ['tiktok-creator','TikTok Creator'],
                ['welding','Welding'],
                ['customer-service','Customer Service'],
              ] as [$val, $labelText])
                <li class="border-b last:border-0 border-zinc-200 dark:border-zinc-800">
                  <label for="radio-{{ $val }}" class="{{ $item }}">
                    <input id="radio-{{ $val }}" type="radio" value="{{ $val }}" name="internship_interest" class="{{ $radio }}" />
                    <span class="text-sm text-zinc-800 dark:text-zinc-200">{{ $labelText }}</span>
                  </label>
                </li>
              @endforeach

              <!-- Other -->
              <li class="p-3">
                <div class="flex items-center gap-3 mb-2">
                  <input id="radio-other" type="radio" value="other" name="internship_interest" class="{{ $radio }}" />
                  <label for="radio-other" class="text-sm text-zinc-800 dark:text-zinc-200">Other</label>
                </div>
                <input type="text" id="radio-other-input" name="internship_interest_other" placeholder="Sebutkan"
                       class="{{ $input }}" />
              </li>
            </ul>
          </div>

          {{-- Schedule info box --}}
          <div>
            <div class="p-5 w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-100">
              <h1 class="text-base md:text-lg font-semibold mb-2">📅 Jadwal Magang</h1>
              <p class="text-sm">Hari magang: <strong>Senin - Sabtu</strong>.</p>
              <p class="text-sm"><strong>Kantor 1:</strong> Shift Pagi (06.30–13.00) & Shift Siang (13.00–21.00 WIB)</p>
              <p class="text-sm"><strong>Kantor 2:</strong> Shift Middle (09.00–17.00) / Pagi & Siang</p>
              <p class="text-sm"><strong>Kantor 4:</strong> Shift Middle (09.00–17.00) / Pagi & Siang</p>
            </div>
          </div>

          {{-- Design Software --}}
          <div>
            <label for="design_software" class="{{ $label }}">
              Jika minat <strong>Desain Grafis</strong> / <strong>UI/UX</strong>, software apa yang dikuasai?
              <span class="{{ $help }}">(Jika bukan, isi “-”)</span>
            </label>
            <input id="design_software" name="design_software" placeholder="Contoh: Figma, Photoshop" required class="{{ $input }}" />
          </div>

          {{-- DM topic --}}
          <div>
            <label for="video_software" class="{{ $label }}">
              Jika minat <strong>Digital Marketing</strong>, materi apa yang ingin dipraktikkan?
              <span class="{{ $help }}">(Jika bukan, isi “-”)</span>
            </label>
            <input id="video_software" name="video_software" placeholder="Contoh: Konten organik, Iklan, SEO" required class="{{ $input }}" />
          </div>

          {{-- Programming Languages --}}
          <div>
            <label for="programming_languages" class="{{ $label }}">
              Jika minat <strong>Programmer</strong>, bahasa pemrograman yang dikuasai?
              <span class="{{ $help }}">(Jika bukan, isi “-”)</span>
            </label>
            <input id="programming_languages" name="programming_languages" placeholder="Contoh: PHP, JS, Python" required class="{{ $input }}" />
          </div>

          {{-- Digital marketing type --}}
          <div>
            <h3 class="{{ $label }}">Jika memilih Digital Marketing, materi yang dipilih</h3>
            <ul class="{{ $group }}">
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="radio-organic" class="{{ $item }}">
                  <input id="radio-organic" type="radio" value="organic" name="digital_marketing_type" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">
                    Digital Marketing Organic
                    <span class="block text-xs text-zinc-500 dark:text-zinc-400">(gratis, tanpa dana iklan)</span>
                  </span>
                </label>
              </li>
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="radio-ads" class="{{ $item }}">
                  <input id="radio-ads" type="radio" value="ads" name="digital_marketing_type" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">
                    Digital Marketing Ads (FB/IG Ads)
                    <span class="block text-xs text-zinc-500 dark:text-zinc-400">(min. 30K/hari selama berjalan)</span>
                  </span>
                </label>
              </li>
              <li class="p-3">
                <div class="flex items-center gap-3 mb-2">
                  <input id="radio-other-marketing-2" type="radio" value="other2" name="digital_marketing_type" class="{{ $radio }}" />
                  <label for="radio-other-marketing-2" class="text-sm text-zinc-800 dark:text-zinc-200">Other</label>
                </div>
                <input id="radio-other-marketing-2-input" name="digital_marketing_type_other" placeholder="Sebutkan" class="{{ $input }}" />
              </li>
            </ul>
          </div>

          {{-- Laptop Equipment --}}
          <div>
            <h3 class="{{ $label }}">Apakah memiliki laptop untuk magang?</h3>
            <ul class="{{ $group }}">
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="radio-yes-laptop" class="{{ $item }}">
                  <input id="radio-yes-laptop" type="radio" value="yes-laptop" name="laptop_equipment" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">YA ADA</span>
                </label>
              </li>
              <li>
                <label for="radio-no-laptop" class="{{ $item }}">
                  <input id="radio-no-laptop" type="radio" value="no-laptop" name="laptop_equipment" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">TIDAK ADA</span>
                </label>
              </li>
            </ul>
          </div>

          {{-- Owned Tools --}}
          <div>
            <h3 class="{{ $label }}">Jika YA, alat apa yang dapat dibawa?</h3>
            <ul class="{{ $group }}">
              @foreach ([
                ['tool-corel-photoshop','Laptop dengan Corel & Photoshop'],
                ['tool-adobe-video','Laptop dengan Premiere/Final Cut/After Effects'],
                ['tool-dslr','Kamera DSLR'],
                ['tool-laptop-netbook','Laptop / Netbook'],
              ] as [$id, $text])
                <li class="border-b last:border-0 border-zinc-200 dark:border-zinc-800">
                  <label for="{{ $id }}" class="{{ $item }}">
                    <input id="{{ $id }}" type="checkbox" value="{{ $id }}" name="owned_tools[]" class="{{ $check }}" />
                    <span class="text-sm text-zinc-800 dark:text-zinc-200">{{ $text }}</span>
                  </label>
                </li>
              @endforeach

              <!-- Other -->
              <li class="p-3">
                <div class="flex items-center gap-3 mb-2">
                  <input id="tool-other-checkbox" type="checkbox" value="other" name="owned_tools[]" class="{{ $check }}" />
                  <label for="tool-other-checkbox" class="text-sm text-zinc-800 dark:text-zinc-200">Lainnya</label>
                </div>
                <input type="text" id="tool-other-checkbox-input" name="owned_tools_other" placeholder="Sebutkan alat lainnya" class="{{ $input }}" />
              </li>
            </ul>
          </div>

          {{-- Start & End Date --}}
          <div>
            <label class="{{ $label }}">
                Kapan rencana mulai Magang/PKL?
                <span class="{{ $help }}">Tulis lengkap tanggal, bulan, tahun & durasi</span>
            </label>
            <div id="date-range-picker" date-rangepicker class="flex flex-col sm:flex-row items-center gap-4">
                
                <!-- Start Date -->
                <div class="relative w-full sm:max-w-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <!-- Kalender Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                        class="w-5 h-5 text-zinc-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 
                            21h15a1.5 1.5 0 001.5-1.5V7.5a1.5 1.5 0 
                            00-1.5-1.5h-15A1.5 1.5 0 003 7.5v12a1.5 
                            1.5 0 001.5 1.5z"/>
                    </svg>
                </div>
                <input id="start_date" datepicker datepicker-autohide type="text" name="start_date"
                    value="{{ request('start_date') }}"
                    class="{{ $input }} pl-10"
                    placeholder="Tanggal mulai">
                </div>

                <span class="text-zinc-700 dark:text-zinc-300">s/d</span>

                <!-- End Date -->
                <div class="relative w-full sm:max-w-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <!-- Kalender Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                        class="w-5 h-5 text-zinc-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 
                            21h15a1.5 1.5 0 001.5-1.5V7.5a1.5 1.5 0 
                            00-1.5-1.5h-15A1.5 1.5 0 003 7.5v12a1.5 
                            1.5 0 001.5 1.5z"/>
                    </svg>
                </div>
                <input id="end_date" datepicker datepicker-autohide type="text" name="end_date"
                    value="{{ request('end_date') }}"
                    class="{{ $input }} pl-10"
                    placeholder="Tanggal selesai">
                </div>
                </div>
            </div>

          {{-- Info source --}}
          <div>
            <h3 class="{{ $label }}">Anda tahu info magang ini dari mana?</h3>
            <ul class="{{ $group }}">
              @foreach (['website'=>'Website','instagram'=>'Instagram','twitter'=>'Twitter','glints'=>'Glints','youtube'=>'YouTube'] as $val => $text)
                <li class="border-b last:border-0 border-zinc-200 dark:border-zinc-800">
                  <label for="{{ $val }}-checkbox" class="{{ $item }}">
                    <input id="{{ $val }}-checkbox" type="checkbox" value="{{ $val }}" name="internship_info_sources[]" class="{{ $check }}" />
                    <span class="text-sm text-zinc-800 dark:text-zinc-200">{{ $text }}</span>
                  </label>
                </li>
              @endforeach

              <li class="p-3">
                <div class="flex items-center gap-3 mb-2">
                  <input id="tool-other-checkbox-2" type="checkbox" value="other" name="internship_info_other" class="{{ $check }}" />
                  <label for="tool-other-checkbox-2" class="text-sm text-zinc-800 dark:text-zinc-200">Other</label>
                </div>
                <input id="tool-other-checkbox-input-2" name="internship_info_other_text" placeholder="Sebutkan sumber lain"
                       class="{{ $input }}" />
              </li>
            </ul>
          </div>

          {{-- Uploads --}}
          <div class="space-y-6">
            <div>
              <label for="file-input-1" class="{{ $label }}">
                Upload <strong>CV, Scan KTP/KTM, Portofolio</strong>
                <span class="{{ $help }}">Format PDF</span>
              </label>
              <input id="file-input-1" type="file" name="cv_ktp_portofolio_pdf"
                     class="{{ $input }} file:mr-4 file:py-2 file:px-3 file:rounded-md file:border-0
                            file:bg-emerald-600 file:text-white hover:file:bg-emerald-700" />
            </div>

            <div>
              <label for="file-input-2" class="{{ $label }}">Upload <strong>Portofolio Visual</strong></label>
              <p class="{{ $help }}">JPG (desainer) atau link YouTube (videografer)</p>
              <input id="file-input-2" type="file" name="portofolio_visual"
                     class="{{ $input }} file:mr-4 file:py-2 file:px-3 file:rounded-md file:border-0
                            file:bg-emerald-600 file:text-white hover:file:bg-emerald-700" />
            </div>
          </div>

          {{-- Current Activities --}}
          <div>
            <label for="current-activities" class="{{ $label }}">Kegiatan Anda saat ini selain magang/PKL?</label>
            <p class="{{ $help }}">Jika ada, mohon sebutkan</p>
            <input id="current-activities" name="current_activities" placeholder="Tuliskan jawaban Anda..." class="{{ $input }}" />
          </div>

          {{-- Boarding Info --}}
          <div>
            <label class="{{ $label }}">Butuh info kos/kontrakan dekat kantor?</label>
            <ul class="{{ $group }}">
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="boarding-info-yes" class="{{ $item }}">
                  <input id="boarding-info-yes" type="radio" value="yes" name="boarding_info" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">YA</span>
                </label>
              </li>
              <li>
                <label for="boarding-info-no" class="{{ $item }}">
                  <input id="boarding-info-no" type="radio" value="no" name="boarding_info" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">TIDAK</span>
                </label>
              </li>
            </ul>
          </div>

          {{-- Family status --}}
          <div>
            <h3 class="{{ $label }}">Apakah Anda sudah berkeluarga?</h3>
            <ul class="{{ $group }}">
              <li class="border-b border-zinc-200 dark:border-zinc-800">
                <label for="family-status-yes" class="{{ $item }}">
                  <input id="family-status-yes" type="radio" value="yes" name="family_status" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">YA</span>
                </label>
              </li>
              <li>
                <label for="family-status-no" class="{{ $item }}">
                  <input id="family-status-no" type="radio" value="no" name="family_status" class="{{ $radio }}" />
                  <span class="text-sm text-zinc-800 dark:text-zinc-200">TIDAK</span>
                </label>
              </li>
            </ul>
          </div>

          {{-- Parent WA --}}
          <div>
            <label for="parent-wa-contact" class="{{ $label }}">No. HP Aktif (WA) Wali / Ortu</label>
            <input id="parent-wa-contact" name="parent_wa_contact" placeholder="0812 1111 8888 (Bapak Adi Pangestu)" class="{{ $input }}" />
          </div>

          {{-- Instagram --}}
          <div>
            <label for="social-media-link" class="{{ $label }}">Sosial Media (Instagram)</label>
            <input id="social-media-link" name="social_media_instagram" placeholder="username / link profil" class="{{ $input }}" />
          </div>

          {{-- Info Box unpaid --}}
          <div>
            <div class="p-5 rounded-lg border border-amber-300 dark:border-amber-600 bg-amber-50 dark:bg-amber-900/20 text-amber-900 dark:text-amber-100">
              <h1 class="text-sm md:text-base leading-relaxed">
                Program Magang ini bersifat <span class="font-semibold">unpaid / tidak bergaji</span>.
                Jika Anda setuju, silakan <strong>SUBMIT</strong> dan <strong>KONFIRMASI</strong> ke WA Admin
                <span class="font-semibold">0895 2900 2944</span> dengan pesan:
                <span class="italic">"SAYA SUDAH ISI FORM"</span>. Terima kasih 🙏
              </h1>
            </div>
          </div>

          <button type="submit"
            class="w-full sm:w-auto inline-flex justify-center items-center gap-2
                   px-5 py-3 rounded-lg text-sm font-medium
                   bg-emerald-600 hover:bg-emerald-700 text-white
                   focus:outline-none focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800">
            Kirim Formulir
          </button>
        </form>
      </section>
    </div>
  </div>
</body>
</html>

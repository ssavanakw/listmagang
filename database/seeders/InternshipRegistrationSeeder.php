<?php

namespace Database\Seeders;

use App\Models\InternshipRegistration as IR;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InternshipRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // ======== Kumpulan data Indonesia ========
        $firstM = ['Muhammad','Ahmad','Nur','Rizky','Agus','Budi','Andi','Fajar','Arief','Rian','Bayu','Rizal','Fadil','Dwi','Dimas','Eko','Yoga','Ilham','Fauzan','Rafli','Yusuf','Heri','Dede','Galang','Aditya','Bagus','Zaki','Iqbal','Rangga','Reza'];
        $firstF = ['Siti','Nurul','Putri','Ayu','Dewi','Lia','Nadia','Intan','Rani','Dinda','Citra','Wulan','Anisa','Maya','Bella','Sarah','Rizka','Nabila','Fitri','Aulia','Tyas','Niken','Mega','Hana','Zahra','Indah','Silvia','Risa','Yuni','Vina'];
        $last    = ['Pratama','Saputra','Permana','Ramadhan','Santoso','Hidayat','Wijaya','Wibowo','Prasetyo','Fauzi','Kurniawan','Setiawan','Maulana','Pangestu','Firmansyah','Alamsyah','Siregar','Simanjuntak','Sihombing','Nugroho','Susanto','Santika','Lestari','Safitri','Utami','Herlambang','Cahyono','Kusuma','Wardani','Puspitasari'];

        $cities  = ['Jakarta','Bandung','Bekasi','Depok','Tangerang','Bogor','Cimahi','Cirebon','Tasikmalaya','Semarang','Solo','Yogyakarta','Magelang','Surabaya','Sidoarjo','Gresik','Malang','Kediri','Jember','Banyuwangi','Denpasar','Mataram','Medan','Padang','Pekanbaru','Palembang','Lampung','Pontianak','Banjarmasin','Makassar'];
        $faculties = ['Teknik','Ilmu Komputer','Ekonomi & Bisnis','Ilmu Sosial & Politik','Seni & Desain','Kedokteran','Pertanian','Vokasi'];
        $prodis = [
            'Informatika','Sistem Informasi','Teknik Industri','Teknik Elektro','Teknik Mesin',
            'Manajemen','Akuntansi','Ilmu Komunikasi','Desain Komunikasi Visual','Desain Produk',
            'Agribisnis','Teknik Sipil','Teknik Arsitektur','Teknologi Informasi','Ilmu Hukum'
        ];
        $universitas = [
            'Universitas Gadjah Mada','Universitas Indonesia','Institut Teknologi Bandung','Universitas Brawijaya',
            'Universitas Negeri Yogyakarta','Universitas Diponegoro','Universitas Sebelas Maret','Universitas Airlangga',
            'Universitas Islam Indonesia','Universitas Muhammadiyah Yogyakarta','Universitas Telkom','Universitas Negeri Malang',
            'Politeknik Negeri Malang','Politeknik Negeri Bandung','Politeknik Elektronika Negeri Surabaya',
            'SMK Negeri 1 Yogyakarta','SMK Negeri 2 Surabaya','SMK Telkom Malang'
        ];

        $genderVals = ['Laki-laki','Perempuan'];
        $internTypes = ['Magang Mandiri','Magang Kampus','PKL','Kampus Merdeka'];
        $arrangements = ['Onsite','Hibrida','Remote'];
        $currentStatus = ['Mahasiswa/Pelajar','Lulusan Baru','Karyawan','Tidak Bekerja'];

        $interests = [
            'Manajer Proyek','Administrasi','Sumber Daya Manusia (HR)','UI/UX',
            'Programmer (Front End / Backend)','Fotografer','Videografer','Desainer Grafis',
            'Spesialis Media Sosial','Penulis Konten','Perencana Konten','Penjualan & Pemasaran',
            'Hubungan Masyarakat (Marcomm)','Pemasaran Digital','Kreator TikTok','Pengelasan','Layanan Pelanggan'
        ];

        $engAbility = ['Saya bisa','Kurang bisa','Tidak bisa'];
        $dmType     = ['Organik','Iklan (FB/IG Ads)','Lainnya'];
        $toolsList  = ['Corel / Photoshop','Adobe Premiere / After Effects','Kamera','Drone','Pen Tablet','Tripod'];
        $sources    = ['website','instagram','twitter','glints','youtube'];

        // Status workflow: bobot agar terasa realistis
        $statusWeighted = [
            IR::STATUS_ACTIVE, IR::STATUS_ACTIVE, IR::STATUS_ACTIVE, IR::STATUS_ACTIVE,
            IR::STATUS_COMPLETED, IR::STATUS_COMPLETED, IR::STATUS_COMPLETED,
            IR::STATUS_WAITING, IR::STATUS_WAITING,
            IR::STATUS_PENDING,
            IR::STATUS_EXITED
        ];

        // ======== Helper kecil ========
        $pick = fn(array $a) => $a[array_rand($a)];
        $randBool = fn(int $pctTrue = 50) => mt_rand(1,100) <= $pctTrue;

        $randDate = function(string $min, string $max) {
            $minTs = strtotime($min);
            $maxTs = strtotime($max);
            $ts = mt_rand($minTs, $maxTs);
            return date('Y-m-d', $ts);
        };

        $records = [];

        for ($i=1; $i<=150; $i++) {

            // Nama + gender
            $isMale = $randBool(55);
            $fname  = $isMale ? $pick($firstM) : $pick($firstF);
            $lname  = $pick($last);
            // kemungkinan 3 kata
            $mname  = $randBool(35) ? ' '.$pick($last) : '';
            $fullname = trim("$fname$mname $lname");

            // Kota & kampus/prodi
            $city   = $pick($cities);
            $faculty= $pick($faculties);
            $prodi  = $pick($prodis);
            $inst   = $pick($universitas);

            // Tanggal lahir: umur 17–25 (biar cocok pendaftar/mahasiswa)
            $born = $randDate('1999-01-01','2007-12-31');

            // Rentang magang 1–6 bulan, start antara 2023–2025
            $start = $randDate('2023-01-01','2025-12-01');
            $durMonths = mt_rand(1,6);
            $end = Carbon::createFromFormat('Y-m-d',$start)->addMonths($durMonths)->format('Y-m-d');

            // ID mahasiswa/siswa
            $studentId = sprintf('%02d%02d%04d', mt_rand(18,25), mt_rand(1,14), mt_rand(1000,9999));

            // Email & IG
            $userSlug = Str::slug($fullname, '.');
            $email = $userSlug.mt_rand(1,999).'@'.$pick(['gmail.com','yahoo.com','student.umn.ac.id','mail.ugm.ac.id','ui.ac.id','itb.ac.id','um.ac.id','uny.ac.id']);
            $ig = '@'.$userSlug.mt_rand(1,99);

            // Telepon Indonesia (acak, mulai 08)
            $phone = '08'.mt_rand(11,99).mt_rand(100,999).mt_rand(1000,9999);

            // Pembimbing kadang kosong
            $supervisor = $randBool(60) ? '' : ('08'.mt_rand(12,99).mt_rand(100,999).mt_rand(1000,9999).' - '.$pick(['Bapak','Ibu']).' '.$pick($last).' (Dosen/Guru)');

            // Minat & materi
            $interest = $pick($interests);
            $interestOther = $interest === 'Lainnya' ? 'Teknisi IT Lapangan' : '';

            // Software/skill (isi “-” sesuai form bila tidak relevan)
            $designSoft = in_array($interest, ['Desainer Grafis','UI/UX']) ? $pick(['Figma','Photoshop','Illustrator','CorelDRAW','Figma, Photoshop']) : '-';
            $videoSoft  = in_array($interest, ['Pemasaran Digital','Spesialis Media Sosial','Videografer']) ? $pick(['Konten organik','Iklan FB/IG','SEO','Video pendek','Copywriting']) : '-';
            $progLangs  = $interest === 'Programmer (Front End / Backend)' ? $pick(['PHP, JS','Laravel, Vue','Node.js, React','Python, Flask','Go, Echo']) : '-';

            // Digital marketing type
            $dm = $pick($dmType);
            $dmOther = $dm === 'Lainnya' ? $pick(['SEO Teknis','Email Marketing','Tiktok Ads']) : '';

            // Laptop + tools
            $hasLaptop = $randBool(80);
            $laptop   = $hasLaptop ? 'Ya' : 'Tidak';
            $ownTools = $hasLaptop
                ? collect($toolsList)->filter(fn($t)=>$randBool(40))->implode(', ')
                : '';
            if ($ownTools === '' && $hasLaptop && $randBool(25)) {
                $ownTools = $pick($toolsList); // minimal satu
            }
            $ownToolsOther = $randBool(10) ? $pick(['Stabilizer','Green Screen','Lighting Ring','Mic Lavalier']) : '';

            // Sumber info (CSV)
            $info = collect($sources)->filter(fn($x)=>$randBool(35))->implode(', ');
            if ($info === '') $info = $pick($sources);
            $infoOther = $randBool(10) ? $pick(['Teman Kampus','Grup WA Kelas','LinkedIn']) : '';

            // Aktivitas lain
            $activities = $randBool(50) ? '-' : $pick([
                'Organisasi kampus','Freelance desain','Asisten praktikum','Kelas bootcamp','UKM Fotografi','Komunitas open source'
            ]);

            // Boarding & keluarga
            $boarding = $randBool(30) ? 'Ya' : 'Tidak';
            $family   = $randBool(10) ? 'Ya' : 'Tidak';

            // Orang tua / wali
            $ortuName = $pick(['Bapak','Ibu']).' '.$pick($last).' '.$pick(['Santoso','Widodo','Saputra','Herlambang','Kusuma','Wijaya']);
            $ortu = '08'.mt_rand(11,99).mt_rand(100,999).mt_rand(1000,9999)." ($ortuName)";

            // Status workflow
            $wf = $pick($statusWeighted);

            // Alasan
            $reason = $pick([
                'Ingin menambah pengalaman kerja nyata',
                'Mencari bimbingan praktis sesuai jurusan',
                'Butuh tempat magang untuk syarat kampus',
                'Ingin meningkatkan portofolio',
                'Tertarik dengan budaya kerja perusahaan'
            ]);

            // English reading
            $eng = $pick($engAbility);

            // Created_at realistis (12 bulan terakhir)
            $created = Carbon::now()->subDays(mt_rand(0, 365));
            $updated = (clone $created)->addDays(mt_rand(0, 60));

            $records[] = [
                'fullname'                  => $fullname,
                'born_date'                 => $born,                   // Y-m-d (string)
                'student_id'                => $studentId,
                'email'                     => strtolower($email),
                'gender'                    => $isMale ? 'Laki-laki' : 'Perempuan',
                'phone_number'              => $phone,

                'institution_name'          => $inst,
                'study_program'             => $prodi,
                'faculty'                   => $faculty,
                'current_city'              => $city,

                'internship_reason'         => $reason,
                'internship_type'           => $pick($internTypes),    // Indonesia (sesuai form)
                'internship_arrangement'    => $pick($arrangements),   // Onsite/Hibrida/Remote
                'current_status'            => $pick($currentStatus),  // Indonesia

                'english_book_ability'      => $eng,
                'supervisor_contact'        => $supervisor,

                'internship_interest'       => $interest,
                'internship_interest_other' => $interestOther,

                'design_software'           => $designSoft,
                'video_software'            => $videoSoft,
                'programming_languages'     => $progLangs,

                'digital_marketing_type'    => $dm,
                'digital_marketing_type_other' => $dmOther,

                'laptop_equipment'          => $laptop,
                'owned_tools'               => $ownTools,
                'owned_tools_other'         => $ownToolsOther,

                'start_date'                => $start, // Y-m-d (string)
                'end_date'                  => $end,   // Y-m-d (string)

                'internship_info_sources'   => $info,      // csv: website, instagram, ...
                'internship_info_other'     => $infoOther,

                'cv_ktp_portofolio_pdf'     => null,
                'portofolio_visual'         => null,

                'current_activities'        => $activities,
                'boarding_info'             => $boarding,
                'family_status'             => $family,

                'parent_wa_contact'         => $ortu,
                'social_media_instagram'    => $ig,

                'internship_status'         => $wf,

                'created_at'                => $created,
                'updated_at'                => $updated,
            ];
        }

        // Insert batch (biar cepat)
        // Jika DB strict soal mass assignment timestamps, IR::insert() sudah oke karena kita set created_at/updated_at.
        IR::insert($records);
    }
}

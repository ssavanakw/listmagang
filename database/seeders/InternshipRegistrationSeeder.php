<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;
use App\Models\InternshipRegistration as IR;

class InternshipRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $genders        = ['Laki-laki','Perempuan'];
        $internTypes    = ['Kampus Merdeka','Mandiri','Kerjasama Kampus'];
        $arrangements   = ['Onsite','Hybrid','Remote']; // kalau di form cuma WFO, pakai ['Onsite']
        $currentStatus  = ['Student','Fresh Graduate'];
        $engRead        = ['Ya','Tidak'];
        $dmTypes        = ['Organik','Iklan','SEO','Lainnya'];
        $infoSources    = ['Instagram','Website','LinkedIn','Teman','Kampus','Youtube','Lainnya'];
        $boardingVals   = ['Tinggal dengan orang tua','Kost','Kontrak'];
        $familyVals     = ['Sudah','Belum'];
        $cities         = ['Jakarta','Bandung','Surabaya','Yogyakarta','Semarang','Depok','Bekasi','Malang','Denpasar','Makassar','Balikpapan','Pontianak','Samarinda','Banjarmasin'];

        $universities   = [
            'Universitas Indonesia','Institut Teknologi Bandung','Universitas Gadjah Mada',
            'Universitas Airlangga','Universitas Brawijaya','Universitas Negeri Jakarta',
            'Universitas Hasanuddin','Universitas Diponegoro','Universitas Padjadjaran',
            'Politeknik Negeri Bandung'
        ];

        $faculties      = ['Fakultas Teknik','Fakultas Ekonomi & Bisnis','Fakultas Ilmu Komputer','Fakultas Ilmu Sosial & Politik','Fakultas Seni Rupa & Desain'];
        $prodis         = ['Informatika','Sistem Informasi','Ilmu Komunikasi','Manajemen','Akuntansi','Teknik Industri','Desain Komunikasi Visual'];

        $interests      = ['UI/UX','Frontend','Backend','Video Editing','Design Grafis','Digital Marketing','Content Writer','Social Media','Photographer','Videographer','Project Manager','Administration','HR','Public Relation','TikTok Creator','Marketing & Sales'];

        $workflow       = ['new','active','completed','exited','pending'];

        foreach (range(1, 150) as $i) {
            $start  = Carbon::today()->subMonths(rand(0, 6))->subDays(rand(0, 25));
            $end    = (clone $start)->addMonths(rand(1, 4))->addDays(rand(0, 15));

            // Distribusi status
            $status = $workflow[array_rand($workflow)];
            if ($i % 5 === 0) $status = 'completed';
            if ($i % 9 === 0) $status = 'active';

            // Tools (checkbox-like -> string koma)
            $ownedTools = $faker->randomElements(
                ['Corel/Photoshop','Adobe Video','Kamera DSLR','Laptop/Netbook'],
                rand(0, 3)
            );
            $ownedToolsStr = empty($ownedTools) ? null : implode(', ', $ownedTools);

            // Sumber info (checkbox-like -> string koma)
            $pickedSources = $faker->randomElements($infoSources, rand(1, 3));
            $sourcesStr    = implode(', ', $pickedSources);
            $infoOther     = in_array('Lainnya', $pickedSources) ? $faker->word() : null;

            IR::create([
                'fullname'                   => $faker->name(),
                'born_date'                  => $faker->dateTimeBetween('-24 years', '-17 years'),
                'student_id'                 => strtoupper($faker->bothify('NIM########')),
                'email'                      => $faker->unique()->safeEmail(),
                'gender'                     => $faker->randomElement($genders),
                'phone_number'               => '08'.$faker->numerify('##########'),
                'institution_name'           => $faker->randomElement($universities),
                'study_program'              => $faker->randomElement($prodis),
                'faculty'                    => $faker->randomElement($faculties),
                'current_city'               => $faker->randomElement($cities),
                'internship_reason'          => $faker->sentence(8),
                'internship_type'            => $faker->randomElement($internTypes),
                'internship_arrangement'     => $faker->randomElement($arrangements),
                'current_status'             => $faker->randomElement($currentStatus),
                'english_book_ability'       => $faker->randomElement($engRead),
                'supervisor_contact'         => $faker->name().' - 08'.$faker->numerify('##########'),

                'internship_interest'        => $faker->randomElement($interests),
                'internship_interest_other'  => $faker->boolean(15) ? $faker->words(2, true) : null,

                'design_software'            => $faker->boolean(60) ? 'Figma, Photoshop' : null,
                'video_software'             => $faker->boolean(40) ? 'Premiere, CapCut' : null,
                'programming_languages'      => $faker->boolean(50) ? $faker->randomElement(['PHP, JavaScript','Python, JavaScript','Go, JavaScript','Java, Kotlin']) : null,

                'digital_marketing_type'     => $faker->randomElement($dmTypes),
                'digital_marketing_type_other'=> $faker->boolean(20) ? $faker->randomElement(['Affiliate','Email Marketing','KOL']) : null,

                'laptop_equipment'           => $faker->randomElement(['Ada','Tidak']),
                'owned_tools'                => $ownedToolsStr,
                'owned_tools_other'          => $faker->boolean(20) ? $faker->randomElement(['Ring Light','Tripod','Mic Clip-On']) : null,

                'start_date'                 => $start,
                'end_date'                   => $end,

                'internship_info_sources'    => $sourcesStr,
                'internship_info_other'      => $infoOther,

                'current_activities'         => $faker->randomElement(['Kuliah','Freelance','Belum Bekerja']),
                'boarding_info'              => $faker->randomElement($boardingVals),
                'family_status'              => $faker->randomElement($familyVals),
                'parent_wa_contact'          => '08'.$faker->numerify('##########'),
                'social_media_instagram'     => '@'.$faker->userName(),

                'cv_ktp_portofolio_pdf'      => null,
                'portofolio_visual'          => null,

                'internship_status'          => $status,
                'created_at'                 => $faker->dateTimeBetween('-6 months','-3 days'),
                'updated_at'                 => now(),
            ]);
        }
    }
}

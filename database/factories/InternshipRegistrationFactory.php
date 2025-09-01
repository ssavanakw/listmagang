<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipRegistrationFactory extends Factory
{
    protected $model = \App\Models\InternshipRegistration::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');

        $status = $faker->randomElement(['new','active','completed','exited','pending']);
        $start  = $faker->dateTimeBetween('-6 months', '+1 month');
        $end    = (clone $start)->modify('+'.mt_rand(30,120).' days');

        return [
            'fullname' => $faker->name(),
            'born_date' => $faker->dateTimeBetween('-26 years', '-18 years'),
            'student_id' => (string) $faker->numerify('##########'),
            'email' => $faker->unique()->safeEmail(),
            'internship_status' => $status,
            'gender' => $faker->randomElement(['Laki-laki','Perempuan']),
            'phone_number' => '08'.$faker->numerify('##########'),
            'institution_name' => $faker->randomElement(['Universitas Indonesia','Institut Teknologi Bandung','Universitas Gadjah Mada','Universitas Brawijaya','Politeknik Negeri Bandung']),
            'study_program' => $faker->randomElement(['Informatika','Sistem Informasi','DKV','Manajemen','Akuntansi','Teknik Industri']),
            'faculty' => $faker->randomElement(['FTI','FEB','FIK','FT','FISIP']),
            'current_city' => $faker->city(),
            'internship_reason' => $faker->sentence(8),
            'internship_type' => $faker->randomElement(['Kampus Merdeka','Mandiri','Kerjasama Kampus']),
            'internship_arrangement' => $faker->randomElement(['Remote','Hybrid','Onsite']),
            'current_status' => $faker->randomElement(['Student','Fresh Graduate']),
            'english_book_ability' => $faker->randomElement(['Ya','Tidak']),
            'supervisor_contact' => $faker->name().' - 08'.$faker->numerify('##########'),
            'internship_interest' => $faker->randomElement(['UI/UX','Frontend','Backend','Video Editing','Design Grafis','Digital Marketing']),
            'internship_interest_other' => $faker->boolean(20) ? $faker->word() : null,
            'design_software' => $faker->boolean(60) ? 'Figma, Photoshop' : null,
            'video_software' => $faker->boolean(40) ? 'Premiere, CapCut' : null,
            'programming_languages' => $faker->boolean(50) ? 'PHP, JavaScript, Python' : null,
            'digital_marketing_type' => $faker->boolean(40) ? 'SEO, Ads' : null,
            'digital_marketing_type_other' => $faker->boolean(20) ? 'Copywriting' : null,
            'laptop_equipment' => $faker->randomElement(['Ada','Tidak']),
            'owned_tools' => $faker->boolean(30) ? 'Kamera' : null,
            'owned_tools_other' => $faker->boolean(20) ? 'Mic' : null,
            'start_date' => $start,
            'end_date' => $end,
            'internship_info_sources' => $faker->randomElement(['Instagram','Website','LinkedIn','Teman']),
            'internship_info_other' => null,
            'current_activities' => $faker->randomElement(['Kuliah','Freelance','Belum Bekerja']),
            'boarding_info' => $faker->randomElement(['Tinggal dengan orang tua','Kost','Kontrak']),
            'family_status' => $faker->randomElement(['Sudah','Belum']),
            'parent_wa_contact' => '08'.$faker->numerify('##########'),
            'social_media_instagram' => '@'.$faker->userName(),
            'cv_ktp_portofolio_pdf' => null,
            'portofolio_visual' => null,
            'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipRegistrationFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['new','active','completed','exited','pending']);

        $start = null;
        $end   = null;
        if ($status === 'active') {
            $start = $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d');
        } elseif (in_array($status, ['completed','exited'], true)) {
            $startDt = $this->faker->dateTimeBetween('-12 months', '-2 months');
            $endDt   = $this->faker->dateTimeBetween($startDt, 'now');
            $start   = $startDt->format('Y-m-d');
            $end     = $endDt->format('Y-m-d');
        }

        return [
            'fullname' => $this->faker->name(),
            'born_date' => $this->faker->date('Y-m-d'),
            'student_id' => $this->faker->unique()->numerify('NIM#######'),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'phone_number' => $this->faker->phoneNumber(),
            'institution_name' => $this->faker->company(),
            'study_program' => $this->faker->word(),
            'faculty' => $this->faker->word(),
            'current_city' => $this->faker->city(),
            'internship_reason' => $this->faker->sentence(),
            'internship_type' => $this->faker->randomElement(['Full Time', 'Part Time']),
            'internship_arrangement' => $this->faker->randomElement(['Online', 'Offline', 'Hybrid']),
            'current_status' => $this->faker->randomElement(['Student', 'Fresh Graduate']),
            'english_book_ability' => $this->faker->randomElement(['Good', 'Fair', 'Poor']),
            'supervisor_contact' => $this->faker->phoneNumber(),
            'internship_interest' => $this->faker->word(),
            'internship_interest_other' => $this->faker->optional()->word(),
            'design_software' => $this->faker->optional()->word(),
            'video_software' => $this->faker->optional()->word(),
            'programming_languages' => $this->faker->optional()->randomElement(['PHP', 'JavaScript', 'Python']),
            'digital_marketing_type' => $this->faker->optional()->word(),
            'digital_marketing_type_other' => $this->faker->optional()->word(),
            'laptop_equipment' => $this->faker->optional()->randomElement(['Yes', 'No']),

            // ⬇⬇ encode ke JSON (kolom kamu bertipe string/varchar)
            'owned_tools' => $this->faker->boolean(70)
                ? json_encode($this->faker->randomElements(
                    ['Laptop','Kamera','Tripod','Mic','Tablet'],
                    $this->faker->numberBetween(1,3)
                ))
                : null,
            'owned_tools_other' => $this->faker->optional()->word(),

            'start_date' => $start,
            'end_date'   => $end,

            // ⬇⬇ encode ke JSON juga
            'internship_info_sources' => json_encode($this->faker->randomElements(
                ['Instagram','Teman','Website','Poster','Kampus'],
                $this->faker->numberBetween(1,3)
            )),
            'internship_info_other' => $this->faker->optional()->sentence(),

            'cv_ktp_portofolio_pdf' => 'dummy_cv.pdf',
            'portofolio_visual' => 'dummy_portofolio.png',
            'current_activities' => $this->faker->optional()->sentence(),
            'boarding_info' => $this->faker->randomElement(['Yes', 'No']),
            'family_status' => $this->faker->randomElement(['Single', 'Married']),
            'parent_wa_contact' => $this->faker->phoneNumber(),
            'social_media_instagram' => '@' . $this->faker->userName(),

            // status workflow
            'internship_status' => $status,
        ];
    }
}

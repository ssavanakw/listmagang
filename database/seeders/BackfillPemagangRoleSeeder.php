<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InternshipRegistration as IR;

class BackfillPemagangRoleSeeder extends Seeder
{
    public function run(): void
    {
        IR::with('user')
            ->where('internship_status', IR::STATUS_ACCEPTED)
            ->chunk(200, function ($registrations) {
                foreach ($registrations as $ir) {
                    $user = $ir->user;
                    if ($user && mb_strtolower($user->role ?? '') !== 'pemagang') {
                        $user->role = 'pemagang';
                        $user->save();
                        $this->command->info("✔️ Role 'pemagang' diberikan ke {$user->name}");
                    }
                }
            });
    }
}


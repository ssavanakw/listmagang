<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InternshipRegistration as IR;
use Carbon\Carbon;

class UpdateInternshipStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-internship-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil semua magang yang memiliki tanggal mulai dan status selain "aktif"
        $registrations = IR::whereNotNull('start_date') // Pastikan ada tanggal mulai
            ->where('internship_status', '!=', IR::STATUS_ACTIVE) // Jangan update yang sudah aktif
            ->get();

        // Periksa setiap magang
        foreach ($registrations as $registration) {
            // Cek jika tanggal mulai sudah tercapai
            if (Carbon::parse($registration->start_date)->isToday()) {
                // Jika sudah, update status menjadi "active"
                $registration->internship_status = IR::STATUS_ACTIVE;
                $registration->save();

                // Tampilkan info bahwa status telah diperbarui
                $this->info("Status magang untuk {$registration->fullname} diperbarui ke 'aktif'");
            }
        }

        // Beri info bahwa command sudah selesai dijalankan
        $this->info('Proses update status magang selesai.');
    }
}

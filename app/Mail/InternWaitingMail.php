<?php
namespace App\Mail;

use App\Models\InternshipRegistration as IR;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InternWaitingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $intern;

    public function __construct(IR $intern)
    {
        $this->intern = $intern;
    }

    public function build()
    {
        return $this->markdown('emails.intern.waiting') // Pastikan file markdown email sesuai
            ->subject('Status Pendaftaran Magang Anda Kembali ke Menunggu')
            ->with([
                'intern' => $this->intern,
            ]);
    }
}

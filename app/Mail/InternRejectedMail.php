<?php
namespace App\Mail;

use App\Models\InternshipRegistration as IR;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InternRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $intern;

    public function __construct(IR $intern)
    {
        $this->intern = $intern;
    }

    public function build()
    {
        return $this->markdown('emails.intern.rejected') // Pastikan file markdown email sesuai
            ->subject('Pendaftaran Magang Anda Ditolak')
            ->with([
                'intern' => $this->intern,
            ]);
    }
}

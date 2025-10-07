@component('mail::message')
# Pendaftaran Magang Anda Kembali ke Status "Menunggu"

Halo {{ $intern->fullname }},

Pendaftaran magang Anda saat ini berada dalam status **"Menunggu"**. Kami belum bisa melanjutkan ke tahap berikutnya saat ini.

Berikut adalah ringkasan data Anda:

- Program/Divisi: {{ $intern->internship_interest ?: '-' }}
- Periode: 
  @if($intern->start_date) {{ \Carbon\Carbon::parse($intern->start_date)->translatedFormat('j F Y') }} @endif
  @if($intern->end_date) s/d {{ \Carbon\Carbon::parse($intern->end_date)->translatedFormat('j F Y') }} @endif
- Lokasi/Kota saat ini: {{ $intern->current_city ?: '-' }}

Kami akan menghubungi Anda jika ada pembaruan lebih lanjut. Terima kasih atas pengertiannya.

Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.

@component('mail::button', ['url' => route('user.dashboard')])
Buka Dashboard
@endcomponent

Terima kasih,  
**Seven Inc.**
@endcomponent

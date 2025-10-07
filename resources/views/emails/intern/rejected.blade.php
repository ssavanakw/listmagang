@component('mail::message')
# Pendaftaran Magang Anda DITOLAK

Halo {{ $intern->fullname }},

Kami sangat menghargai minat Anda untuk bergabung dengan tim kami di **Seven Inc.** Namun, setelah mempertimbangkan semua pendaftar, kami mohon maaf untuk memberitahukan bahwa pendaftaran magang Anda **DITOLAK** untuk saat ini.

Berikut adalah ringkasan data Anda:

- Program/Divisi: {{ $intern->internship_interest ?: '-' }}
- Periode: 
  @if($intern->start_date) {{ \Carbon\Carbon::parse($intern->start_date)->translatedFormat('j F Y') }} @endif
  @if($intern->end_date) s/d {{ \Carbon\Carbon::parse($intern->end_date)->translatedFormat('j F Y') }} @endif
- Lokasi/Kota saat ini: {{ $intern->current_city ?: '-' }}

Kami mengucapkan terima kasih atas minat Anda dan harap tetap semangat dalam mencari kesempatan lain.

Jika Anda memiliki pertanyaan atau ingin mendapatkan masukan lebih lanjut, jangan ragu untuk menghubungi kami.

@component('mail::button', ['url' => route('user.dashboard')])
Buka Dashboard
@endcomponent

Terima kasih,  
**Seven Inc.**
@endcomponent

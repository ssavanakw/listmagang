@component('mail::message')
# Selamat, {{ $intern->fullname }}!

Pendaftaran magang Anda **DITERIMA**. Berikut ringkasannya:

- Program/Divisi: {{ $intern->internship_interest ?: '-' }}
- Periode: 
  @if($intern->start_date) {{ \Carbon\Carbon::parse($intern->start_date)->translatedFormat('j F Y') }} @endif
  @if($intern->end_date) s/d {{ \Carbon\Carbon::parse($intern->end_date)->translatedFormat('j F Y') }} @endif
- Lokasi/Kota saat ini: {{ $intern->current_city ?: '-' }}

Tim kami akan menghubungi Anda untuk tahap berikutnya.  
Jika ada pertanyaan, balas saja email ini.

@component('mail::button', ['url' => route('user.dashboard')])
Buka Dashboard
@endcomponent

Terima kasih,  
**Seven Inc.**
@endcomponent

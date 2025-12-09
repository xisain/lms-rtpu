
@component('mail::message')
# Halo {{ $userName }},

Terima kasih telah mendaftar di **{{ config('app.name') }}**.
Kami menghargai waktu dan usaha Anda untuk bergabung.

Namun setelah peninjauan, tim kami **menolak permintaan pendaftaran akun Anda** dengan alasan berikut:

@component('mail::panel')
{{ $reason ?? 'Alasan tidak tersedia. Silakan hubungi tim dukungan untuk informasi lebih lanjut.' }}
@endcomponent

Jika Anda ingin mengajukan banding atau memperbaiki data yang mungkin kurang, silakan klik tombol di bawah ini untuk menghubungi tim dukungan atau mengirim ulang dokumen:

@component('mail::button', ['url' => $appealUrl ?? 'mailto:muhammadhusainalghazali@gmail.com'])
Ajukan Banding / Hubungi Dukungan
@endcomponent

Atau balas langsung ke: {{ $supportEmail ?? 'rtpu@pnj.ac.id' }}.

Terima kasih,
Salam hangat,<br>
Tim {{ config('app.name') }}

@component('mail::subcopy')
Jika Anda merasa keputusan ini salah, mohon sertakan detail (nama lengkap, email terdaftar, dan bukti pendukung) saat menghubungi tim dukungan.
@endcomponent
@endcomponent

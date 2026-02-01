@component('mail::message')
# Selamat Datang, {{ $user->name }}!

Akun Anda telah berhasil dibuat. Kami sangat senang Anda bergabung dengan kami.

## Detail Akun Anda

**Nama:** {{ $user->name }}
**Email:** {{ $user->email }}

## Informasi Course Anda

@component('mail::panel')
**Nama Course:** {{ $course->nama_course ?? 'N/A' }} <br/>
**Deskripsi:** {{ $course->description ?? 'N/A' }} <br/>
**Penanggung Jawab:** {{ $course->teacher->name ?? 'N/A' }} <br/>
@endcomponent

## Langkah Selanjutnya

Untuk keamanan akun Anda, silakan buat password dengan mengklik tombol di bawah ini:

@component('mail::button', ['url' => $resetUrl, 'color' => 'success'])
Buat Password
@endcomponent
@component('mail::button', ['url'=>$linkWhatsapp])
Gabung Grup Whatsapp
@endcomponent

Link ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak membuat akun ini, abaikan email ini.

Jika Anda mengalami masalah dengan tombol di atas, salin dan tempel URL berikut ke browser Anda:

{{ $resetUrl }}

---

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent

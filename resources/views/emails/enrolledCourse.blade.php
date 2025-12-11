@component('mail::message')
#  Selamat! Anda Telah Bergabung

Halo **{{ $studentName }}**,

Selamat! Anda telah berhasil mendaftar di course kami.

@component('mail::panel')
## 📚 Detail Course

**Nama Course:** {{ $course->nama_course ?? 'N/A' }}

**Deskripsi:** {{ $course->description ?? 'Tidak ada deskripsi' }}

**Instruktur:** {{ $course->teacher->name ?? 'N/A' }}

**Tanggal Mulai:** {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d F Y') : 'Segera' }}
@endcomponent

@if($course->whatsapp_group)
@component('mail::button', ['url' => $course->whatsapp_group, 'color' => 'success'])
 Gabung Grup WhatsApp
@endcomponent
@endif

@component('mail::button', ['url' => $courseUrl])
Mulai Belajar Sekarang
@endcomponent

## Langkah Selanjutnya:

1. Akses course melalui dashboard Anda
2. Bergabung dengan grup WhatsApp untuk diskusi (jika tersedia)
3. Mulai pelajari materi pertama
4. Jangan ragu untuk bertanya kepada instruktur

---

Jika Anda memiliki pertanyaan, silakan hubungi kami atau instruktur course.

Terima kasih,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Ini adalah email otomatis. Jika Anda tidak mendaftar course ini, abaikan email ini atau hubungi support kami.
@endcomponent
@endcomponent

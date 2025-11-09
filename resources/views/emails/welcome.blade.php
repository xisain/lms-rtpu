@component('mail::message')
# Selamat Datang, {{ $user->name }}!

Akun Anda telah berhasil dibuat. Kami sangat senang Anda bergabung dengan kami.

## Detail Akun Anda

**Nama:** {{ $user->name }}
**Email:** {{ $user->email }}

## Informasi Subscription

@component('mail::panel')
**Paket:** {{ $plan->name ?? 'N/A' }}
**Durasi:** {{ $plan->duration_in_days ?? 0 }} hari
**Status:** {{ ucfirst($subscription->status) }}
**Mulai:** {{ $subscription->starts_at->format('d M Y, H:i') }}
**Berakhir:** {{ $subscription->ends_at->format('d M Y, H:i') }}
@endcomponent

## Langkah Selanjutnya

Untuk keamanan akun Anda, silakan buat password dengan mengklik tombol di bawah ini:

@component('mail::button', ['url' => $resetUrl, 'color' => 'success'])
Buat Password
@endcomponent

Link ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak membuat akun ini, abaikan email ini.

Jika Anda mengalami masalah dengan tombol di atas, salin dan tempel URL berikut ke browser Anda:

{{ $resetUrl }}

---

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent

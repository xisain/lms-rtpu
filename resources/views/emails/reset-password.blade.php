@component('mail::message')
# Reset Password

Halo{{ $userName ? ' ' . $userName : '' }},

Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.

@component('mail::button', ['url' => $resetLink])
Reset Password
@endcomponent

Link reset password ini akan kadaluarsa dalam **60 menit**.

Jika Anda tidak melakukan permintaan reset password, abaikan email ini dan tidak ada perubahan yang akan terjadi pada akun Anda.

Terima kasih,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Jika Anda mengalami masalah saat mengklik tombol "Reset Password", salin dan tempel URL berikut ke browser Anda:
[{{ $resetLink }}]({{ $resetLink }})
@endcomponent
@endcomponent

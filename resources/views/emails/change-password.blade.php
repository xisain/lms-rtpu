@component('mail::message')
# Password Berhasil Diperbarui

Halo{{ isset($userName) && $userName ? ' ' . $userName : '' }},

Kata sandi untuk akun Anda di **{{ config('app.name') }}** telah berhasil diperbarui.

Jika Anda **tidak melakukan perubahan ini**, segera amankan akun Anda dengan melakukan reset password menggunakan tautan berikut:

@component('mail::button', ['url' => route('forgetpassword')])
Reset Password Sekarang
@endcomponent

Terima kasih telah menjaga keamanan akun Anda!
Salam hangat,<br>
**{{ config('app.name') }} Team**

@component('mail::subcopy')
Email ini dikirim otomatis oleh sistem kami untuk alasan keamanan.
@endcomponent
@endcomponent

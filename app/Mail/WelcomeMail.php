<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;
    public $subscription;
    public $plan;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $subscription, $plan)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->plan = $plan;

        // Hapus token lama jika ada
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        // Generate password reset token & URL
        $token = Password::broker()->createToken($user);
        $this->resetUrl = url(route('resetpassword', [
            'token' => $token,
            'email' => $user->email,
        ], false));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang - Akun Anda Telah Dibuat',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

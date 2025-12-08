<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TolakAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $reason;
    public $appealUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName = null, string $reason = null, string $appealUrl = null)
    {
        $this->userName = $userName;
        $this->reason = $reason;
        $this->appealUrl = $appealUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan: Akun Anda Ditolak',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tolakAccount',
            with: [
                'userName' => $this->userName,
                'reason' => $this->reason,
                'appealUrl' => $this->appealUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class enrollCourse extends Mailable
{
    use Queueable, SerializesModels;

    public $course;

    public $studentName;

    public $courseUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($course, $studentName)
    {
        $this->course = $course;
        $this->studentName = $studentName;
        $this->courseUrl = config('app.url') . '/course/' . $course->slugs;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Berhasil Mendaftar !',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.enrolledCourse'
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

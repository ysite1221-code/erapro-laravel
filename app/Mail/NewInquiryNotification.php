<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewInquiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $userName,
        public readonly string $purpose,
        public readonly string $inquiryUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【ERAPRO】新しい相談リクエストが届きました',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new_inquiry',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

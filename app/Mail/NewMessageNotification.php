<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $senderName,
        public readonly string $messagePreview,
        public readonly string $inquiryUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【ERAPRO】新着メッセージが届きました',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new_message',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

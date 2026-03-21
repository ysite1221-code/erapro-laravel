<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryStatusChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $agentName,
        public readonly string $statusLabel,
        public readonly string $inquiryUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【ERAPRO】相談のステータスが更新されました',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.inquiry_status_changed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

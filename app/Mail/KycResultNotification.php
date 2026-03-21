<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KycResultNotification extends Mailable
{
    use Queueable, SerializesModels;

    /** @param int $status 2=承認済み / 9=否認 */
    public function __construct(
        public readonly string $agentName,
        public readonly int    $status,      // 2 or 9
        public readonly string $dashboardUrl,
    ) {}

    public function envelope(): Envelope
    {
        $label = $this->status === 2 ? '承認' : '否認';
        return new Envelope(
            subject: "【ERAPRO】本人確認（KYC）の審査結果：{$label}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.kyc_result',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

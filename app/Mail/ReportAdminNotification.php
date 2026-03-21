<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * 通報発生時に運営に送る通知メール
 * 旧PHP版: report_act.php の send_mail(MAIL_FROM_EMAIL, ...) 相当
 */
class ReportAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $reporterType,   // 'user' or 'agent'
        public readonly string $reporterName,
        public readonly string $reporterEmail,
        public readonly string $targetType,     // 'agent' or 'user'
        public readonly string $targetName,
        public readonly string $targetEmail,
        public readonly string $reason,
        public readonly string $adminReportsUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【ERAPRO】通報が届きました',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.report_admin_notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

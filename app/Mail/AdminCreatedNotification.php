<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Admin $admin,
        public readonly string $plainPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【ERAPRO】管理者アカウントが発行されました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_created',
            with: [
                'adminName'     => $this->admin->name,
                'adminEmail'    => $this->admin->email,
                'plainPassword' => $this->plainPassword,
                'loginUrl'      => route('admin.login'),
            ],
        );
    }
}

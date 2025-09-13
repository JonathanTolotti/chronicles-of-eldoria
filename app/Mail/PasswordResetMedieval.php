<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMedieval extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $resetUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🛡️ Redefinição de Senha - Chronicles of Eldoria',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.password-reset-medieval',
            with: [
                'resetUrl' => $this->resetUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

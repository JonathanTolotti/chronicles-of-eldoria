<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMedieval extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public $url
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚔️ Verificação de Email - Chronicles of Eldoria',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.verify-email-medieval',
            with: [
                'url' => $this->url,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
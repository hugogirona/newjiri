<?php

namespace App\Mail;

use App\Models\Jiri;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JiriCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public function __construct(public Jiri $jiri)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jiri Created',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.jiri-created',
            with: ['jiri'=> $this->jiri]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

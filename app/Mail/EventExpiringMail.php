<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\User;

class EventExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;
    public $daysLeft;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, User $user, int $daysLeft)
    {
        $this->event = $event;
        $this->user = $user;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->daysLeft === 0 
            ? 'URGENTE: Evento Expira HOJE - ' . $this->event->title
            : ($this->daysLeft === 1 
                ? 'Aviso: Evento Expira AMANHÃ - ' . $this->event->title
                : 'Aviso: Evento Expira em ' . $this->daysLeft . ' dias - ' . $this->event->title);
                
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-expiring',
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
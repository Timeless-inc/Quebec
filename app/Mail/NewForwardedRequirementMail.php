<?php

namespace App\Mail;

use App\Models\User;
use App\Models\ApplicationRequest; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewForwardedRequirementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $recipient;
    public $applicationRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $recipient, ApplicationRequest $applicationRequest)
    {
        $this->recipient = $recipient;
        $this->applicationRequest = $applicationRequest;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Novo Requerimento Recebido para Análise',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
      return new Content(markdown: 'emails.requirements.forwarded');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
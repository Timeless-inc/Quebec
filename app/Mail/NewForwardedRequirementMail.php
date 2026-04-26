<?php

namespace App\Mail;

use App\Models\User;
use App\Models\ApplicationRequest;
use App\Models\RequestForwarding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewForwardedRequirementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $recipient;
    public $applicationRequest;
    public $forwarding;

    /**
     * Create a new message instance.
     *
     * @param User $recipient
     * @param ApplicationRequest $applicationRequest
     * @param RequestForwarding $forwarding
     * @return void
     */
    public function __construct(User $recipient, ApplicationRequest $applicationRequest, RequestForwarding $forwarding)
    {
        $this->recipient = $recipient;
        $this->applicationRequest = $applicationRequest;
        $this->forwarding = $forwarding;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        $requestId = $this->applicationRequest->key ?? $this->applicationRequest->id ?? 'ID-desconhecido';
        
        return new Envelope(
            subject: 'Novo Requerimento Encaminhado para Análise #' . $requestId,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-forwarded-requirement',
            with: [
                'recipient' => $this->recipient,
                'applicationRequest' => $this->applicationRequest,
                'forwarding' => $this->forwarding,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}

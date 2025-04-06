<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NewEventMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $event;
    public $user;

    /**
     * Create a new message instance.
     * 
     * @param Event $event
     * @param User $user
     */
    public function __construct(Event $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        Log::info('Construindo email de novo evento', [
            'event_id' => $this->event->id,
            'user_id' => $this->user->id
        ]);
        
        return $this->subject('Novo Evento: ' . $this->event->title)
                    ->view('emails.new-event');
    }
}
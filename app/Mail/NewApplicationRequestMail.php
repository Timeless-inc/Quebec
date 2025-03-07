<?php

namespace App\Mail;

use App\Models\ApplicationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NewApplicationRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $user;

    /**
     * Create a new message instance.
     * 
     * @param ApplicationRequest $request
     * @param mixed $user - User model ou stdClass com propriedades name e email
     */
    public function __construct(ApplicationRequest $request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        
        $cacheKey = 'email_sent_' . $this->request->id . '_new_request';
        
        if (Cache::has($cacheKey)) {
            Log::info('Email duplicado evitado', [
                'request_id' => $this->request->id,
                'email' => $this->request->email
            ]);
            return $this; 
        }
        
        
        Cache::put($cacheKey, true, now()->addHours(6));
        
        return $this->subject('Nova Solicitação Recebida')
                    ->view('emails.new-request');
    }
}
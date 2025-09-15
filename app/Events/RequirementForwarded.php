<?php

namespace App\Events;

use App\Models\User;
use App\Models\RequestForwarding;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequirementForwarded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $forwarding;
    public $recipient;

    /**
     * @param \App\Models\RequestForwarding 
     * @param \App\Models\User 
     */
    public function __construct(RequestForwarding $forwarding, User $recipient) 
    {
        $this->forwarding = $forwarding;
        $this->recipient = $recipient;
    }
}
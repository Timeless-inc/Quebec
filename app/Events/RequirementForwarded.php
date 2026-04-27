<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\RequestForwarding;
use App\Models\User;

class RequirementForwarded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $forwarding;
    public $recipient;
    public $forwarding_id;

    /**
     * Create a new event instance.
     *
     * @param  RequestForwarding  $forwarding
     * @param  User  $recipient
     * @return void
     */
    public function __construct(RequestForwarding $forwarding, User $recipient)
    {
        $this->forwarding = $forwarding;
        $this->recipient = $recipient;
        $this->forwarding_id = $forwarding->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        
        // Notifica o admin sobre novos encaminhamentos
        $channels[] = new PrivateChannel('admin.forwardings');
        
        // Notifica o destinatário (Professor, Coordenador, Diretor Geral)
        if ($this->recipient) {
            $channels[] = new PrivateChannel('App.Models.User.' . $this->recipient->id);
        }
        
        return $channels;
    }
}

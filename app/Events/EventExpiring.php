<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\User;

class EventExpiring
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;
    public $user;
    public $daysLeft;

    /**
     * Create a new event instance.
     */
    public function __construct(Event $event, User $user, int $daysLeft)
    {
        $this->event = $event;
        $this->user = $user;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
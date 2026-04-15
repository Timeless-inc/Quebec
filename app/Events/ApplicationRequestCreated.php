<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ApplicationRequest;

class ApplicationRequestCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $request_id; 
    public $request_key;

    /**
     * Create a new event instance.
     *
     * @param  ApplicationRequest  $request
     * @return void
     */
    public function __construct(ApplicationRequest $request)
    {
        $this->request = $request;
        $this->request_id = $request->id;
        $this->request_key = $request->key; 
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        
        $channels[] = new PrivateChannel('admin.requerimentos');

        $aluno = \App\Models\User::where('cpf', $this->request->cpf)
            ->orWhere('email', $this->request->email)
            ->first();

        if ($aluno) {
            $channels[] = new PrivateChannel('App.Models.User.' . $aluno->id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'ApplicationRequestCreated';
    }
}
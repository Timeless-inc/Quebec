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

class ApplicationStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(ApplicationRequest $request, string $oldStatus, string $newStatus)
    {
        $this->request = $request;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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

        // Pessoas que receberam esse requerimento via encaminhamento (Professor, etc)
        $receivers_ids = \App\Models\RequestForwarding::where('requerimento_id', $this->request->id)
            ->pluck('receiver_id')
            ->unique();
            
        foreach ($receivers_ids as $receiver_id) {
            $channels[] = new PrivateChannel('App.Models.User.' . $receiver_id);
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
        return 'ApplicationStatusChanged';
    }
}

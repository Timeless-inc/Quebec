<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class NotifyEventEnding extends Command
{
    protected $signature = 'events:notify-ending';
    protected $description = 'Notifica usuários sobre eventos próximos do fim';

    public function handle()
    {
        // Encontra eventos que terminam em 24 horas
        $tomorrow = Carbon::now()->addDay();
        $events = Event::whereDate('end_date', $tomorrow->toDateString())->get();
        
        if ($events->count() > 0) {
            // Notificar todos os alunos
            $alunos = User::where('role', 'Aluno')->get();
            
            foreach ($events as $event) {
                foreach ($alunos as $aluno) {
                    Notification::create([
                        'user_id' => $aluno->id,
                        'title' => 'Evento Finalizando',
                        'message' => "O evento '{$event->title}' termina amanhã! Não perca, são os últimos momentos.",
                        'is_read' => false
                    ]);
                }
            }
            
            $this->info('Notificações enviadas para eventos próximos do fim.');
        } else {
            $this->info('Nenhum evento termina nas próximas 24 horas.');
        }
    }
}
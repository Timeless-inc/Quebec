<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
            Log::info('Iniciando notificações de eventos finalizando', ['count' => $events->count()]);
            
            // Notificar todos os alunos
            $alunos = User::where('role', 'Aluno')->get();
            
            foreach ($events as $event) {
                foreach ($alunos as $aluno) {
                    try {
                        Notification::create([
                            'user_id' => $aluno->id,
                            'title' => 'Evento Finalizando',
                            'message' => "O evento '{$event->title}' termina amanhã! Não perca, são os últimos momentos.",
                            'event_type' => 'event_expiring',
                            'related_id' => $event->id,
                            'is_read' => false
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Erro ao criar notificação de evento expirando', [
                            'user_id' => $aluno->id,
                            'event_id' => $event->id,
                            'message' => $e->getMessage()
                        ]);
                    }
                }
            }
            
            Log::info('Notificações de eventos finalizando enviadas com sucesso');
            $this->info('Notificações enviadas para eventos próximos do fim.');
        } else {
            $this->info('Nenhum evento termina nas próximas 24 horas.');
        }
    }
}
<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\EventCreated;
use App\Mail\NewEventMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendEventNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    public $backoff = 10;

    /**
     * Handle the event.
     */
    public function handle(EventCreated $event): void
    {
        Log::info('Listener SendEventNotification iniciado');

        try {
            if (!$event->event) {
                Log::error('Dados do evento inválidos');
                return;
            }

            $eventId = $event->event->id ?? 'unknown';
            
            Log::info('Processando email de novo evento', [
                'id' => $eventId,
                'title' => $event->event->title ?? 'unknown'
            ]);

            // Evita duplicidade de emails usando cache
            $cacheKey = 'event_email_processed_' . $eventId;
            if (Cache::has($cacheKey)) {
                Log::info('Email de evento já processado', ['event_id' => $eventId]);
                return;
            }
            
            // Busca todos os usuários cadastrados
            $users = User::all();
            
            if ($users->isEmpty()) {
                Log::warning('Nenhum usuário encontrado para enviar notificação de evento');
                return;
            }
            
            Log::info('Enviando email de novo evento para ' . $users->count() . ' usuários');
            
            foreach ($users as $user) {
                // Evita envios duplicados para o mesmo usuário
                $userCacheKey = 'event_email_' . $eventId . '_user_' . $user->id;
                if (Cache::has($userCacheKey)) {
                    continue;
                }
                
                Mail::to($user->email)->queue(new NewEventMail($event->event, $user));
                
                Cache::put($userCacheKey, true, now()->addHours(24));
                
                Log::info('Email de novo evento enfileirado', [
                    'email' => $user->email,
                    'event_id' => $eventId
                ]);
            }
            
            Cache::put($cacheKey, true, now()->addDays(7));
            
        } catch (\Exception $e) {
            Log::error('Erro ao processar email de novo evento', [
                'message' => $e->getMessage(),
                'event_id' => $event->event->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff * ($this->attempts() + 1));
                Log::warning('Reagendando tentativa de envio de email de evento', [
                    'attempt' => $this->attempts(),
                    'next_try_in_seconds' => $this->backoff * ($this->attempts() + 1)
                ]);
            } else {
                $this->fail($e);
                Log::error('Máximo de tentativas atingido, falha no envio de email de evento');
            }
        }
    }
}
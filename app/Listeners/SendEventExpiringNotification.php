<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\EventExpiring;
use App\Mail\EventExpiringMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class SendEventExpiringNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    public $backoff = 10;
    
    /**
     * Handle the event.
     */
    public function handle(EventExpiring $event): void
    {
        Log::info('Listener SendEventExpiringNotification iniciado');
        
        try {
            $eventModel = $event->event;
            $daysLeft = $event->daysLeft;
            $eventId = $eventModel->id ?? 'unknown';
            
            Log::info('Processando email de evento expirando', [
                'event_id' => $eventId,
                'title' => $eventModel->title,
                'days_left' => $daysLeft,
                'end_date' => $eventModel->end_date->format('Y-m-d')
            ]);
            
            $cacheKey = 'expiring_email_sent_' . $eventId . '_' . $daysLeft . 'days';
            if (Cache::has($cacheKey)) {
                Log::info('Email de expiração já enviado para este evento e período', [
                    'event_id' => $eventId,
                    'days_left' => $daysLeft
                ]);
                return;
            }
            
            // Corrigido: usando where('role', 'Aluno') em vez de role('Aluno')
            $users = User::where('role', 'Aluno')->get();
            
            if ($users->isEmpty()) {
                Log::warning('Nenhum usuário com role Aluno encontrado', [
                    'event_id' => $eventId
                ]);
                return;
            }
            
            foreach ($users as $user) {
                if (!$user->email) {
                    Log::error('Email do usuário não encontrado', [
                        'event_id' => $eventId,
                        'user_id' => $user->id
                    ]);
                    continue;
                }
                
                Log::info('Enviando email de aviso de expiração', [
                    'email' => $user->email,
                    'event_id' => $eventId,
                    'days_left' => $daysLeft
                ]);

                Mail::to($user->email)->queue(new EventExpiringMail($eventModel, $user, $daysLeft));
            }
            
            $cacheDuration = $daysLeft > 1 ? now()->addHours(24) : now()->addHours(36);
            Cache::put($cacheKey, true, $cacheDuration);
            
            Log::info('Emails de aviso de expiração enfileirados para alunos', [
                'event_id' => $eventId,
                'days_left' => $daysLeft,
                'total_users' => $users->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao processar emails de aviso de expiração', [
                'message' => $e->getMessage(),
                'event_id' => $event->event->id ?? 'unknown',
                'days_left' => $event->daysLeft,
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff * ($this->attempts() + 1));
                Log::warning('Reagendando tentativa de envio de emails de expiração', [
                    'attempt' => $this->attempts(),
                    'next_try_in_seconds' => $this->backoff * ($this->attempts() + 1)
                ]);
            } else {
                $this->fail($e);
                Log::error('Máximo de tentativas atingido, falha no envio de emails de expiração');
            }
        }
    }
}
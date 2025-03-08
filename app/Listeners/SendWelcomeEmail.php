<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserRegistered;
use App\Mail\WelcomeStudentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;
    
    public $tries = 3;
    public $backoff = 10;
    
    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Log::info('Listener SendWelcomeEmail iniciado');
        
        try {
            
            if (!$event->user || !isset($event->user->email)) {
                Log::error('Dados de usuário inválidos no evento de registro');
                return;
            }
            
            $userId = $event->user->id ?? 'unknown';
            $userEmail = $event->user->email;
            
            Log::info('Processando email de boas-vindas', [
                'id' => $userId,
                'email' => $userEmail,
                'role' => $event->user->role ?? 'unknown'
            ]);
            
            $cacheKey = 'welcome_email_sent_' . $userId;
            if (Cache::has($cacheKey)) {
                Log::info('Email de boas-vindas já enviado', [
                    'user_id' => $userId,
                    'email' => $userEmail
                ]);
                return;
            }
            
            
            if ($event->user->role === 'Aluno') {
                Log::info('Enviando email de boas-vindas', [
                    'email' => $userEmail,
                    'role' => 'Aluno'
                ]);
                
                Mail::to($userEmail)->queue(new WelcomeStudentMail($event->user));
                
                Cache::put($cacheKey, true, now()->addDays(30));
                
                Log::info('Email de boas-vindas enfileirado', [
                    'email' => $userEmail
                ]);
            } else {
                Log::info('Email de boas-vindas não enviado - usuário não é Aluno', [
                    'role' => $event->user->role ?? 'unknown'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar email de boas-vindas', [
                'message' => $e->getMessage(),
                'user_id' => $event->user->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff * ($this->attempts() + 1));
                Log::warning('Reagendando tentativa de envio de email de boas-vindas', [
                    'attempt' => $this->attempts()
                ]);
            } else {
                $this->fail($e);
                Log::error('Máximo de tentativas atingido, falha no envio de email de boas-vindas');
            }
        }
    }
}
<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ApplicationRequestCreated;
use App\Mail\NewApplicationRequestMail;
use App\Models\User;
use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendNewRequestNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    public $backoff = 10; 
    
    /**
     * Handle the event.
     */
    public function handle(ApplicationRequestCreated $event): void
    {
        Log::info('Listener SendNewRequestNotification iniciado');
    
        try {
            
            $request = $event->request;
            $requestId = $event->request_id ?? $request->id ?? null;
            $requestKey = $event->request_key ?? $request->key ?? null;
            
            
            $uniqueId = $requestId ?? $requestKey ?? 'unknown';
            
            Log::info('Processando email de requerimento', [
                'id' => $requestId,
                'key' => $requestKey
            ]);
            
            
            if (!$request || (!$requestId && !$requestKey)) {
                Log::error('Dados de requisição inválidos no evento', [
                    'event_data' => json_encode($event)
                ]);
                return;
            }
            
            
            $cacheKey = 'email_processed_' . $uniqueId;
            if (Cache::has($cacheKey)) {
                Log::info('Email já processado, evitando duplicação', ['id' => $uniqueId]);
                return;
            }
            
            
            if (!isset($request->email)) {
                
                if ($requestId) {
                    $request = ApplicationRequest::find($requestId);
                } elseif ($requestKey) {
                    $request = ApplicationRequest::where('key', $requestKey)->first();
                }
                
                if (!$request || !$request->email) {
                    Log::error('Email não encontrado na requisição', ['id' => $uniqueId]);
                    return;
                }
            }
            
           
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = new \stdClass();
                $user->name = $request->nomeCompleto ?? 'Usuário';
                $user->email = $request->email;
            }
            
            
            $recentEmailKey = 'recent_email_' . $request->email . '_new_request_' . $uniqueId;
            if (Cache::has($recentEmailKey)) {
                Log::info('Email enviado recentemente, evitando reenvio', [
                    'email' => $request->email, 
                    'id' => $uniqueId
                ]);
                return;
            }
            

            Log::info('Enviando email de novo requerimento', [
                'email' => $request->email, 
                'key' => $uniqueId
            ]);
            
            Mail::to($request->email)->queue(new NewApplicationRequestMail($request, $user));
            
            
            Cache::put($cacheKey, true, now()->addHours(24));
            Cache::put($recentEmailKey, true, now()->addHours(6));
            
            Log::info('Email de novo requerimento enfileirado', [
                'email' => $request->email, 
                'key' => $uniqueId
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao processar email de novo requerimento', [
                'message' => $e->getMessage(),
                'id' => $event->request_id ?? $event->request_key ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff * ($this->attempts() + 1));
                Log::warning('Reagendando tentativa de envio de email', [
                    'attempt' => $this->attempts(),
                    'next_try_in_seconds' => $this->backoff * ($this->attempts() + 1)
                ]);
            } else {
                $this->fail($e);
                Log::error('Máximo de tentativas atingido, falha no envio de email');
            }
        }
    }
}
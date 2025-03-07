<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ApplicationStatusChanged;
use App\Mail\ApplicationStatusChangedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\ApplicationRequest;

class SendStatusUpdateNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    public $backoff = 10;
    
    /**
     * Handle the event.
     */
    public function handle(ApplicationStatusChanged $event): void
    {
        Log::info('Listener SendStatusUpdateNotification iniciado');
        
        try {
            
            $request = $event->request;
            $requestId = $request->id ?? 'unknown';
            $requestKey = $request->key ?? null;
            
            
            $uniqueId = $requestId !== 'unknown' ? $requestId : ($requestKey ?? 'unknown');
            
            Log::info('Processando email de mudança de status', [
                'id' => $requestId,
                'key' => $requestKey,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus
            ]);

            $cacheKey = 'status_email_processed_' . $uniqueId . '_' . $event->newStatus;
            if (Cache::has($cacheKey)) {
                Log::info('Email de status já processado', ['request_id' => $uniqueId]);
                return;
            }
            
            if (!$request || !isset($request->email)) {
                
                if ($requestId !== 'unknown') {
                    $request = ApplicationRequest::find($requestId);
                } elseif ($requestKey) {
                    $request = ApplicationRequest::where('key', $requestKey)->first();
                }
                
                if (!$request || !isset($request->email)) {
                    Log::error('Email não encontrado na requisição', ['id' => $uniqueId]);
                    return;
                }
            }
            
            $recentEmailKey = 'recent_status_email_' . $request->email . '_' . $uniqueId . '_' . $event->newStatus;
            if (Cache::has($recentEmailKey)) {
                Log::info('Email de status enviado recentemente, evitando reenvio', [
                    'email' => $request->email,
                    'id' => $uniqueId
                ]);
                return;
            }

            Log::info('Enviando email de atualização de status', [
                'email' => $request->email,
                'id' => $uniqueId,
                'status' => $event->newStatus
            ]);

            Mail::to($request->email)->queue(
                new ApplicationStatusChangedMail(
                    $request,
                    $event->oldStatus,
                    $event->newStatus
                )
            );

            Cache::put($cacheKey, true, now()->addHours(24));
            Cache::put($recentEmailKey, true, now()->addHours(3));
            
            Log::info('Email de atualização de status enfileirado', [
                'email' => $request->email,
                'id' => $uniqueId,
                'status' => $event->newStatus
            ]);
        } catch (\Exception $e) {
            $requestId = $event->request->id ?? 'unknown';
            
            Log::error('Erro ao processar email de atualização de status', [
                'message' => $e->getMessage(),
                'request_id' => $requestId,
                'trace' => $e->getTraceAsString()
            ]);

            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff * ($this->attempts() + 1));
                Log::warning('Reagendando tentativa de envio de email de status', [
                    'attempt' => $this->attempts(),
                    'next_try_in_seconds' => $this->backoff * ($this->attempts() + 1)
                ]);
            } else {
                $this->fail($e);
                Log::error('Máximo de tentativas atingido, falha no envio de email de status');
            }
        }
    }
}
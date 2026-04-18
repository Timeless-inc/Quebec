<?php

namespace App\Listeners;

use App\Events\RequirementForwarded;
use App\Mail\NewForwardedRequirementMail;
use App\Models\ApplicationRequest;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class SendForwardedRequirementNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    public $backoff = 10;

    /**
     * Handle the event.
     * Envia email de notificação quando um requerimento é encaminhado
     * para Coordenador, Professor ou Diretor Geral.
     */
    public function handle(RequirementForwarded $event): void
    {
        Log::info('Listener SendForwardedRequirementNotification iniciado');

        try {
            $recipient = $event->recipient;
            $forwarding = $event->forwarding;
            $uniqueId = $forwarding->id;
            
            // Verifica se o email já foi processado
            $cacheKey = 'email_forwarding_processed_' . $uniqueId;
            if (Cache::has($cacheKey)) {
                Log::info('Email de encaminhamento já processado', [
                    'forwarding_id' => $uniqueId
                ]);
                return;
            }

            // Valida se o destinatário é válido
            if (!$recipient || !$recipient->email) {
                Log::error('Destinatário inválido para notificação de encaminhamento', [
                    'recipient_id' => $recipient->id ?? 'null',
                    'forwarding_id' => $uniqueId
                ]);
                return;
            }

            // Valida se o destinatário pode receber encaminhamentos
            if (!$recipient->canReceiveForwardings()) {
                Log::warning('Destinatário não pode receber encaminhamentos', [
                    'recipient_id' => $recipient->id,
                    'recipient_role' => $recipient->role,
                    'forwarding_id' => $uniqueId
                ]);
                return;
            }

            // Obtém o requerimento associado
            $applicationRequest = $forwarding->requerimento;
            if (!$applicationRequest) {
                Log::error('Requerimento associado não encontrado', [
                    'forwarding_id' => $uniqueId
                ]);
                $this->fail(new \Exception('Requerimento não encontrado para o encaminhamento ID: ' . $uniqueId));
                return;
            }

            Log::info('Enfileirando email de novo encaminhamento', [
                'recipient_email' => $recipient->email,
                'recipient_role' => $recipient->role,
                'forwarding_id' => $uniqueId,
                'requerimento_id' => $applicationRequest->id
            ]);

            // Enfileira o email
            Mail::to($recipient->email)->queue(
                new NewForwardedRequirementMail($recipient, $applicationRequest, $forwarding)
            );

            // Marca como processado no cache
            Cache::put($cacheKey, true, now()->addHours(24));

            Log::info('Email de encaminhamento enfileirado com sucesso', [
                'recipient_email' => $recipient->email,
                'forwarding_id' => $uniqueId
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar email de encaminhamento', [
                'message' => $e->getMessage(),
                'forwarding_id' => $event->forwarding->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff * ($this->attempts() + 1));
                Log::warning('Reagendando tentativa de envio de email de encaminhamento', [
                    'attempt' => $this->attempts(),
                    'next_try_in_seconds' => $this->backoff * ($this->attempts() + 1)
                ]);
            } else {
                $this->fail($e);
                Log::critical('Máximo de tentativas atingido. Falha no envio de email de encaminhamento', [
                    'forwarding_id' => $event->forwarding->id ?? 'unknown'
                ]);
            }
        }
    }
}

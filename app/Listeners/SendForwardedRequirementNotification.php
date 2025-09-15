<?php

namespace App\Listeners;

use App\Events\RequirementForwarded;
use App\Mail\NewForwardedRequirementMail;
use App\Models\ApplicationRequest;
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

    public function handle(RequirementForwarded $event): void
{
    Log::info('Listener SendForwardedRequirementNotification iniciado.');

    try {
        $recipient = $event->recipient;
        $forwarding = $event->forwarding;

        $uniqueId = $forwarding->id;
        $cacheKey = 'email_forwarding_processed_' . $uniqueId;

        if (Cache::has($cacheKey)) {
            Log::info('Email de encaminhamento já processado.', ['forwarding_id' => $uniqueId]);
            return;
        }

        if (empty($recipient->email) || !in_array($recipient->role, ['Professor', 'Coordenador'])) {
            Log::warning('Destinatário inválido para notificação.', ['recipient_id' => $recipient->id]);
            return;
        }

        $applicationRequest = $forwarding->requerimento;

        if (!$applicationRequest) {
            Log::error('Requerimento associado não encontrado.', ['forwarding_id' => $forwarding->id]);
            $this->fail(new \Exception('Requerimento não encontrado para o encaminhamento ID: ' . $forwarding->id));
            return;
        }

        Log::info('Enfileirando e-mail de novo encaminhamento.', ['recipient_email' => $recipient->email]);
        Mail::to($recipient->email)->queue(new NewForwardedRequirementMail($recipient, $applicationRequest));
        
        Cache::put($cacheKey, true, now()->addHours(24));
        Log::info('Email de novo encaminhamento enfileirado com sucesso.');

    } catch (\Exception $e) {
        Log::error('Erro ao processar e-mail de encaminhamento.', ['message' => $e->getMessage()]);
        if ($this->attempts() < $this->tries) {
            $this->release($this->backoff * $this->attempts());
        } else {
            $this->fail($e);
            Log::critical('Máximo de tentativas atingido. Falha permanente no envio de e-mail de encaminhamento.', [
                'forwarding_id' => $event->forwarding->id ?? 'unknown',
            ]);
        }
    }
}
}
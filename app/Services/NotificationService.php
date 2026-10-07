<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Encontra aluno por CPF ou email
     */
    public static function findStudentByRequest($request): ?User
    {
        return User::where('cpf', $request->cpf)
            ->orWhere('email', $request->email)
            ->first();
    }

    /**
     * Criar notificação pop-up genérica
     */
    public static function createNotification(
        User $user,
        string $title,
        string $message,
        string $eventType,
        ?int $relatedId = null
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'event_type' => $eventType,
            'related_id' => $relatedId,
            'is_read' => false
        ]);
    }

    /**
     * Notificar quando novo requerimento é criado
     */
    public static function notifyNewRequest($applicationRequest): void
    {
        try {
            $aluno = self::findStudentByRequest($applicationRequest);

            if ($aluno && $aluno->role === 'Aluno') {
                self::createNotification(
                    $aluno,
                    'Requerimento Criado',
                    'Seu requerimento #' . $applicationRequest->id . ' foi enviado com sucesso! Em breve ele será revisado e você será notificado sobre a atualização.',
                    'request_created',
                    $applicationRequest->id
                );
                Log::info('Notificação pop-up criada com sucesso', [
                    'user_id' => $aluno->id,
                    'request_id' => $applicationRequest->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação pop-up', [
                'message' => $e->getMessage(),
                'request_id' => $applicationRequest->id ?? null
            ]);
        }
    }

    /**
     * Notificar quando status muda
     */
    public static function notifyStatusChange(
        $applicationRequest,
        string $newStatus
    ): void {
        try {
            $aluno = self::findStudentByRequest($applicationRequest);

            if ($aluno && $aluno->role === 'Aluno') {

                $statusText = \App\Services\StatusFormatter::format($newStatus);

                self::createNotification(
                    $aluno,
                    'Status Atualizado',
                    "Seu requerimento #" . $applicationRequest->id . " teve o status atualizado para: {$statusText}",
                    'status_changed',
                    $applicationRequest->id
                );
                Log::info('Notificação de status pop-up criada com sucesso', [
                    'user_id' => $aluno->id,
                    'request_id' => $applicationRequest->id,
                    'new_status' => $newStatus
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação de status', [
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * Notificar quando requerimento é devolvido
     */
    public static function notifyReturned($applicationRequest): void
    {
        try {
            $aluno = self::findStudentByRequest($applicationRequest);
            if ($aluno && $aluno->role === 'Aluno') {
                self::createNotification(
                    $aluno,
                    'Requerimento Devolvido',
                    'Seu requerimento #' . $applicationRequest->id . ' foi devolvido à CRADT. Você será notificado quando houver uma atualização.',
                    'request_returned',
                    $applicationRequest->id
                );
                Log::info('Notificação de devolução pop-up criada via NotificationService', ['user_id' => $aluno->id, 'request_id' => $applicationRequest->id]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação de devolução', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Notificar quando requerimento é encaminhado
     */
    public static function notifyForwarded($applicationRequest): void
    {
        try {
            $aluno = self::findStudentByRequest($applicationRequest);
            if ($aluno && $aluno->role === 'Aluno') {
                self::createNotification(
                    $aluno,
                    'Requerimento Encaminhado',
                    "Seu requerimento #" . $applicationRequest->id . " foi encaminhado para avaliação de um responsável. Você receberá uma notificação quando houver uma atualização.",
                    'requirement_forwarded',
                    $applicationRequest->id
                );
                Log::info('Notificação de encaminhamento pop-up criada via NotificationService', ['user_id' => $aluno->id, 'request_id' => $applicationRequest->id]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificação de encaminhamento', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Notificar sobre a criação de um novo evento acadêmico
     */
    public static function notifyEventCreated($event): void
    {
        try {
            $alunos = User::where('role', 'Aluno')->get();
            $dataInicio = date('d/m/Y', strtotime($event->start_date));
            $dataFim = date('d/m/Y', strtotime($event->end_date));

            foreach ($alunos as $aluno) {
                self::createNotification(
                    $aluno,
                    'Novo Evento Acadêmico',
                    "Um novo evento foi cadastrado: {$event->title}. Data: {$dataInicio} a {$dataFim}",
                    'event_created',
                    $event->id
                );
            }
            Log::info('Notificações de novo evento criadas para alunos via NotificationService', ['event_id' => $event->id]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações de evento', ['message' => $e->getMessage(), 'event_id' => $event->id]);
        }
    }
}
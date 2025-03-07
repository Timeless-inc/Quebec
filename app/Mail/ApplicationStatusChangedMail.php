<?php

namespace App\Mail;

use App\Models\ApplicationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ApplicationStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $request;
    public $oldStatus;
    public $newStatus;
    
    
    public $tries = 3;

    /**
     * Create a new message instance.
     */
    public function __construct(ApplicationRequest $request, string $oldStatus, string $newStatus)
    {
        $this->request = $request;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        try {
            
            if (!$this->request->email) {
                $this->request = ApplicationRequest::find($this->request->id);
                if (!$this->request) {
                    throw new \Exception('Requisição não encontrada após deserialização');
                }
            }
    
            $requestIdentifier = $this->request->key ?? $this->request->id ?? 'ID-desconhecido';
    
            return $this->subject('Atualização de Status da Requisição #' . $requestIdentifier)
                        ->view('emails.request-status-changed')
                        ->with([
                            'request' => $this->request,
                            'oldStatus' => $this->formatStatus($this->oldStatus),
                            'newStatus' => $this->formatStatus($this->newStatus),
                        ]);
        } catch (\Exception $e) {
            Log::error('Erro ao preparar email de status', [
                'error' => $e->getMessage(),
                'request_id' => $this->request->id ?? 'unknown'
            ]);
            throw $e;
        }
    }
    
    /**
     * Formata o status para exibição
     */
    private function formatStatus($status)
    {
        $statusMap = [
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'deferido' => 'Deferido',
            'indeferido' => 'Indeferido',
        ];
        
        return $statusMap[$status] ?? ucfirst($status);
    }
}
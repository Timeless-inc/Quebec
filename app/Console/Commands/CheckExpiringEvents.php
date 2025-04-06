<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class CheckExpiringEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:check-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica eventos que estão expirando e envia notificações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificação de eventos que estão expirando...');
        
        try {
            $notificationResults = Event::notifyExpiringEvents();
            
            $totalNotifications = $notificationResults['today'] + $notificationResults['tomorrow'] + $notificationResults['soon'];
            
            $this->info("Processamento concluído. {$totalNotifications} notificações enviadas:");
            $this->info("- Eventos expirando hoje: {$notificationResults['today']}");
            $this->info("- Eventos expirando amanhã: {$notificationResults['tomorrow']}");
            $this->info("- Eventos expirando em 3 dias: {$notificationResults['soon']}");
            
            Log::info('Comando de verificação de eventos concluído', [
                'notifications' => $notificationResults
            ]);
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erro ao processar eventos: ' . $e->getMessage());
            
            Log::error('Erro no comando de verificação de eventos', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }
}
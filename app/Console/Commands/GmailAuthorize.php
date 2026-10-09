<?php

namespace App\Console\Commands;

use App\Services\GmailOAuth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class GmailAuthorize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmail:authorize {--test= : Envia um e-mail de teste para este endereço depois de autorizar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autoriza a conta do Gmail que envia os e-mails do sistema pela API do Google';

    /**
     * Execute the console command.
     */
    public function handle(GmailOAuth $oauth)
    {
        try {
            $url = $oauth->urlDeAutorizacao();
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->line('1. Abra o endereço abaixo no navegador e entre com a conta que vai enviar os e-mails:');
        $this->newLine();
        $this->line($url);
        $this->newLine();
        $this->line('2. Depois de permitir, o navegador vai para um endereço que começa com http://localhost/?code=...');
        $this->line('   A página não abre mesmo; copie o endereço inteiro da barra do navegador.');
        $this->newLine();

        $entrada = trim((string) $this->ask('Cole aqui o endereço (ou só o código)'));

        // Aceita tanto o endereço de retorno inteiro quanto só o valor de "code"
        parse_str((string) parse_url($entrada, PHP_URL_QUERY), $parametros);
        $codigo = $parametros['code'] ?? $entrada;

        if ($codigo === '') {
            $this->error('Nenhum código informado.');
            return self::FAILURE;
        }

        try {
            $oauth->trocarCodigo($codigo);
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->info('Conta autorizada. Para enviar pela API do Gmail, defina MAIL_MAILER=gmail no .env.');

        if ($destino = $this->option('test')) {
            Mail::mailer('gmail')->raw('E-mail de teste do SRE enviado pela API do Gmail.', function ($mensagem) use ($destino) {
                $mensagem->to($destino)->subject('Teste de envio do SRE');
            });

            $this->info('E-mail de teste enviado para ' . $destino . '.');
        }

        return self::SUCCESS;
    }
}

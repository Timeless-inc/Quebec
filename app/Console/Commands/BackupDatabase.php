<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class BackupDatabase extends Command
{
    private const FILE_PATTERN = 'sre-*.sql.gz';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--no-upload : Gera o dump sem enviar para o remoto do rclone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera um dump compactado do banco e envia para o remoto do rclone (ex.: Google Drive)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $conexao = config('database.connections.' . config('database.default'));

        if (! in_array($conexao['driver'], ['mysql', 'mariadb'])) {
            $this->error('O backup suporta apenas MySQL/MariaDB.');
            return self::FAILURE;
        }

        $diretorio = config('backup.path');
        File::ensureDirectoryExists($diretorio);

        $arquivo = $diretorio . DIRECTORY_SEPARATOR . 'sre-' . now()->format('Ymd-His') . '.sql.gz';

        if (! $this->gerarDump($conexao, $arquivo)) {
            return self::FAILURE;
        }

        $this->info('Dump gerado: ' . $arquivo . ' (' . round(filesize($arquivo) / 1024) . ' KB)');

        $this->removerDumpsAntigos($diretorio);

        if ($this->option('no-upload')) {
            return self::SUCCESS;
        }

        $remoto = config('backup.rclone.remote');

        if (! $remoto) {
            $this->error('BACKUP_RCLONE_REMOTE não está configurado; o dump ficou apenas no servidor.');
            return self::FAILURE;
        }

        return $this->enviarParaRemoto($arquivo, $remoto) ? self::SUCCESS : self::FAILURE;
    }

    private function gerarDump(array $conexao, string $arquivo): bool
    {
        $sql = substr($arquivo, 0, -3);

        // A senha vai por variável de ambiente para não aparecer na lista de processos
        $resultado = Process::forever()
            ->env(['MYSQL_PWD' => (string) $conexao['password']])
            ->run([
                config('backup.mysqldump'),
                '--host=' . $conexao['host'],
                '--port=' . $conexao['port'],
                '--user=' . $conexao['username'],
                '--single-transaction',
                '--routines',
                '--no-tablespaces',
                '--default-character-set=utf8mb4',
                '--result-file=' . $sql,
                $conexao['database'],
            ]);

        if ($resultado->failed()) {
            File::delete($sql);
            $this->error('Falha no mysqldump: ' . trim($resultado->errorOutput()));
            return false;
        }

        $origem = fopen($sql, 'rb');
        $destino = gzopen($arquivo, 'wb9');

        while (! feof($origem)) {
            gzwrite($destino, fread($origem, 1024 * 1024));
        }

        fclose($origem);
        gzclose($destino);
        File::delete($sql);

        return true;
    }

    private function removerDumpsAntigos(string $diretorio): void
    {
        $limite = now()->subDays(config('backup.keep_days'))->getTimestamp();

        foreach (File::glob($diretorio . DIRECTORY_SEPARATOR . self::FILE_PATTERN) as $dump) {
            if (filemtime($dump) < $limite) {
                File::delete($dump);
                $this->line('Dump antigo removido: ' . basename($dump));
            }
        }
    }

    private function enviarParaRemoto(string $arquivo, string $remoto): bool
    {
        $rclone = config('backup.rclone.binary');

        $envio = Process::forever()->run([$rclone, 'copy', $arquivo, $remoto]);

        if ($envio->failed()) {
            $this->error('Falha no envio pelo rclone: ' . trim($envio->errorOutput()));
            return false;
        }

        $this->info('Dump enviado para ' . $remoto);

        $dias = config('backup.rclone.keep_days');

        if ($dias > 0) {
            $limpeza = Process::forever()->run([
                $rclone, 'delete', $remoto, '--min-age', $dias . 'd', '--include', self::FILE_PATTERN,
            ]);

            if ($limpeza->failed()) {
                $this->warn('Não foi possível remover dumps antigos do remoto: ' . trim($limpeza->errorOutput()));
            }
        }

        return true;
    }
}

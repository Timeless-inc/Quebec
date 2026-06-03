<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\ImageProcessor;

class TestUploadCommand extends Command
{
    protected $signature = 'test:upload';
    protected $description = 'Executa teste básico de upload: cria imagem, processa com ImageProcessor e verifica armazenamento';

    public function handle()
    {
        $this->info('Iniciando teste de upload...');

        $tmpDir = storage_path('app/tmp-tests');
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }

        $filePath = $tmpDir . '/test-image.jpg';

        // Criar uma imagem JPEG simples (500x300)
        $img = imagecreatetruecolor(500, 300);
        $bg = imagecolorallocate($img, 200, 200, 200);
        imagefill($img, 0, 0, $bg);
        imagejpeg($img, $filePath, 90);
        imagedestroy($img);

        $this->info('Imagem de teste criada em: ' . $filePath);

        $uploaded = new UploadedFile($filePath, 'test-image.jpg', 'image/jpeg', null, true);

        $processor = app(ImageProcessor::class);

        try {
            $path = $processor->processAndStore($uploaded, 'test-uploads');
            $this->info('ProcessAndStore retornou: ' . $path);

            if (Storage::disk('public')->exists($path)) {
                $this->info('Arquivo gravado com sucesso em disk public: ' . $path);
                return 0;
            }

            $this->error('Arquivo não encontrado após processamento.');
            return 1;
        } catch (\Exception $e) {
            $this->error('Erro ao processar imagem: ' . $e->getMessage());
            return 1;
        }
    }
}

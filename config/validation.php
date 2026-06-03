<?php

return [
    'file_limits' => [
        // em KB
        'pdf' => 2048, // 2 MB
        'pdf_process' => 5120, // 5 MB para PDFs enviados durante processamento/encaminhamento
        'image_upload_max_kb' => 5120, // 5 MB para uploads brutos de imagem
        'image_target_max_width' => 2048, // redimensionar largura máxima
        'image_quality' => 80, // qualidade ao salvar (0-100)
        'total_per_request_kb' => 10240, // 10 MB por requisição (total)
        // Proteções extras
        'php_memory_limit' => '256M', // sugestão para ambiente local/produção leve
        'image_max_pixels' => 30000000, // máximo de pixels (width * height) que processaremos (ex: 30M)
        'client_warning_kb' => 3072, // avisar no cliente se imagem > 3MB
        'client_max_pixels' => 12000000, // no cliente, comprimir se a imagem exceder 12M pixels
        'client_compress_trigger_kb' => 1024, // no cliente, tentar comprimir imagens >= 1MB
    ],

    'allowed_mimes' => 'pdf,jpg,jpeg,png,webp',
];

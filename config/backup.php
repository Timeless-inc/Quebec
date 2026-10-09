<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup do banco de dados
    |--------------------------------------------------------------------------
    |
    | Configurações do comando "php artisan db:backup", que gera um dump
    | compactado do banco e o envia para um remoto do rclone (ex.: Google Drive).
    |
    */

    'path' => env('BACKUP_PATH', storage_path('app/private/backups')),

    'mysqldump' => env('BACKUP_MYSQLDUMP_PATH', 'mysqldump'),

    // Dias que os dumps ficam guardados no servidor
    'keep_days' => (int) env('BACKUP_KEEP_DAYS', 7),

    'rclone' => [
        'binary' => env('BACKUP_RCLONE_PATH', 'rclone'),

        // Remoto e pasta de destino no formato do rclone, ex.: "gdrive:SRE/backups"
        'remote' => env('BACKUP_RCLONE_REMOTE'),

        // Dias que os dumps ficam guardados no remoto (0 = nunca apagar)
        'keep_days' => (int) env('BACKUP_RCLONE_KEEP_DAYS', 30),
    ],

];

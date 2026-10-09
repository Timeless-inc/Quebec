<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Backup diário do banco enviado para o remoto do rclone (ver config/backup.php)
Schedule::command('db:backup')->dailyAt('02:00')->withoutOverlapping();

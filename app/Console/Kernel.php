<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            \App\Models\Event::where('end_date', '<', \Carbon\Carbon::today())->delete();
        })->dailyAt('00:00');

        $schedule->command('events:process-expired')->dailyAt('00:00');
        
        // Executar diariamente às 9:00 para notificar eventos que terminam em breve aos alunos
        $schedule->command('events:notify-ending')->dailyAt('09:00');

        $schedule->command('events:check-expiring')->dailyAt('00:00');
        
       
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        
        require base_path('routes/console.php');
    }
}
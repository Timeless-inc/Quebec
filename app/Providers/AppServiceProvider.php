<?php

namespace App\Providers;

use App\Mail\Transport\GmailApiTransport;
use App\Services\GmailOAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Mail::extend('gmail', fn () => new GmailApiTransport($this->app->make(GmailOAuth::class)));

        if (!file_exists(public_path('storage'))) {
            $this->app->make('files')->link(
                storage_path('app/public'), public_path('storage')
            );
        }
    }
}

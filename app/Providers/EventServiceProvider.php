<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Events\ApplicationRequestCreated;
use App\Events\ApplicationStatusChanged;
use App\Events\EventCreated;
use App\Listeners\SendEventNotification;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\SendNewRequestNotification;
use App\Listeners\SendStatusUpdateNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        
        Registered::class => [
    
        ],
        
        UserRegistered::class => [
            SendWelcomeEmail::class,
        ],
        
        ApplicationRequestCreated::class => [
            SendNewRequestNotification::class,
        ],
        
        ApplicationStatusChanged::class => [
            SendStatusUpdateNotification::class,
        ],

        EventCreated::class => [
            SendEventNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
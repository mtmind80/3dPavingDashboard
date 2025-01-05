<?php

namespace App\Providers;

use App\Events\SomethingToNotify;
use App\Listeners\NotifyRecipient;
use App\Listeners\SetLocaleAsUserStoredLanguage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \Illuminate\Auth\Events\Login::class => [
            SetLocaleAsUserStoredLanguage::class,
        ],

        SomethingToNotify::class => [
            NotifyRecipient::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}

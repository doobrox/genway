<?php

namespace App\Providers;

use App\Events\SendMails;
use App\Listeners\RecalculateCart;
use App\Listeners\SendConfirmedPaymentEmails;
use App\Listeners\SendOrderEmails;
use App\Listeners\SendNewsletterEmails;
use App\Listeners\SendRegisterEmails;
use App\Listeners\SendTemplateEmails;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            RecalculateCart::class,
        ],
        Logout::class => [
            RecalculateCart::class,
        ],
        SendMails::class => [
            SendConfirmedPaymentEmails::class,
            SendOrderEmails::class,
            SendNewsletterEmails::class,
            SendRegisterEmails::class,
            SendTemplateEmails::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

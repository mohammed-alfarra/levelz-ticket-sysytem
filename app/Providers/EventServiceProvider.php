<?php

namespace App\Providers;

use App\Events\UserDeleted;
use App\Listeners\ReplaceUserValuesWithStarsAndNull;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string<UserDeleted>, array<class-string<ReplaceUserValuesWithStarsAndNull>|class-string<SendEmailVerificationNotification>>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void {}
}

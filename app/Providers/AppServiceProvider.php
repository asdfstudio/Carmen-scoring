<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Schema::defaultStringLength(191);

      VerifyEmail::toMailUsing(function ($notifiable) {
        $verifyUrl = URL::temporarySignedRoute(
          'verification.verify',
          now()->addMinutes(60),
          ['id' => $notifiable->getKey()]
        );

        return (new MailMessage())
          ->subject('Verify Email Address')
          ->line('Please click the button below to verify your email address.')
          ->action('Verify Email Address', $verifyUrl)
          ->line('If you did not create an account, no further action is required.');
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
    }
}

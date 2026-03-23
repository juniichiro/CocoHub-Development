<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
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
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
        return (new MailMessage())
            ->subject('Reset Your CocoHub Password')
            ->greeting('Hello, ' . ($notifiable->first_name ?? 'Valued Customer') . '!')
            ->line('We received a request to reset the password for your CocoHub account.')
            ->action('Reset Password', url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request this, please ignore this email.')
            ->salutation('Warmly, The Lumiere Team');
    });
    }
}

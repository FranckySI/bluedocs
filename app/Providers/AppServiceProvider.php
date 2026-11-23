<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        VerifyEmail::toMailUsing(function (mixed $user, string $url): MailMessage {
            return (new MailMessage)
                ->subject('Vérifiez votre adresse e-mail - BlueDocs')
                ->greeting('Bonjour '.$user->name.',')
                ->line('Un administrateur a créé votre compte éditeur sur BlueDocs.')
                ->line('Connectez-vous avec le mot de passe provisoire communiqué séparément par votre administrateur : [Se connecter à BlueDocs]('.route('login').').')
                ->line('Après connexion, confirmez votre adresse e-mail avec le bouton ci-dessous.')
                ->action('Confirmer mon adresse e-mail', $url)
                ->line('Une fois votre adresse confirmée, vous devrez obligatoirement choisir un nouveau mot de passe.')
                ->salutation('L’équipe BlueDocs');
        });

        Gate::define('create-users', function ($user) {
            return $user->role === 'admin';
        });

        View::composer('layouts.app', function ($view): void {
            $view->with('documentationStyle', Setting::documentationStyle());
        });
    }
}

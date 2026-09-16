<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Carbon;

class UpdateLastLoginAt
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        // Met à jour la date sans toucher au champ 'updated_at'
        $user->withoutTimestamps(function () use ($user) {
            $user->update([
                'last_login_at' => Carbon::now(),
            ]);
        });
    }
}

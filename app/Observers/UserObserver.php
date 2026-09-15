<?php

namespace App\Observers;

use App\Models\Block;
use App\Models\Plan;
use App\Models\Resume;
use App\Models\User;
use App\Models\Widget;
use Illuminate\Support\Carbon;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // $user->sendEmailVerificationNotification();
        if (!$user->hasSubscription()) {
            $user->initializeAccount();
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

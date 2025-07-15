<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use IlluminateAuthEventsAuthenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;

class StoreSessionAfterLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
     public function handle(Authenticated $event): void
    {
        // Tambah data ke session
        Session::put('user_name', $event->user->name);
        Session::put('user_role', $event->user->role ?? 'guest');
        Session::put('user_id', $event->user->id);
    }
}

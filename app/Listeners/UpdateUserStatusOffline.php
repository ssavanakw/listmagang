<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\User;

class UpdateUserStatusOffline
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        // Pastikan user yang keluar adalah instance dari User
        $user = $event->user;

        if ($user instanceof User) {
            // Perbarui status online menjadi false
            $user->is_online = false;
            $user->save();  // Pastikan save() bisa dijalankan
        }
    }
}

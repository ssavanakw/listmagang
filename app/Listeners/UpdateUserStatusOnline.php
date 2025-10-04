<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UpdateUserStatusOnline
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // Pastikan user yang masuk adalah instance dari User
        $user = $event->user;

        if ($user instanceof User) {
            // Perbarui status online menjadi true
            $user->is_online = true;
            $user->save();  // Pastikan save() bisa dijalankan
        }
    }
}

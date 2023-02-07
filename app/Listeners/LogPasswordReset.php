<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use App\Interfaces\User\UserLogInterface;

class LogPasswordReset
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserLogInterface $userLogInterface)
    {
        $this->userLogInterface = $userLogInterface;
    }

    /**
     * Handle the event.
     *
     * @param  PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        $this->userLogInterface->store($event->user->id, 'Password Reset', request()->ip(), request()->header('user-agent'));
    }
}

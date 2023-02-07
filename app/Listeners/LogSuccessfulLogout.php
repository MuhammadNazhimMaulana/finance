<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Interfaces\User\UserLogInterface;

class LogSuccessfulLogout
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $this->userLogInterface->store($event->user->id, 'logout', request()->ip(), request()->header('user-agent'));
    }
}

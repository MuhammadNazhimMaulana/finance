<?php

namespace App\Listeners;

use App\Events\ProfileUpdated;
use App\Interfaces\User\UserLogInterface;

class LogProfileUpdated
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
     * @param  ProfileUpdated  $event
     * @return void
     */
    public function handle(ProfileUpdated $event)
    {
        $this->userLogInterface->store($event->user->id, 'Update Profile', request()->ip(), request()->header('user-agent'));
    }
}

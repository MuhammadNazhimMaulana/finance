<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Interfaces\User\UserLogInterface;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogin
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $this->userLogInterface->store($event->user->id, 'login', request()->ip(), request()->header('user-agent'));
        Auth::logoutOtherDevices(request('password'));
    }
}

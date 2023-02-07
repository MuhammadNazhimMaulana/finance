<?php

namespace App\Observers;

use App\Models\UserPin;
use App\Repositories\User\UserLogRepository;

class UserPinObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the user pin "created" event.
     *
     * @param  \App\Models\UserPin  $userPin
     * @return void
     */
    public function created(UserPin $userPin)
    {
        $this->createLog('CREATE PIN #'.$userPin->id);
    }

    /**
     * Handle the user pin "updated" event.
     *
     * @param  \App\Models\UserPin  $userPin
     * @return void
     */
    public function updated(UserPin $userPin)
    {
        $this->createLog('UPDATE PIN #'.$userPin->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

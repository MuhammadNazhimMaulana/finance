<?php

namespace App\Observers;

use App\User;
use App\Repositories\User\UserLogRepository;
use Illuminate\Support\Facades\Auth;

class AdminObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $this->createLog('CREATE #'.$user->id);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $this->createLog('UPDATE ADMIN #'.$user->id);
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $this->createLog('DELETE ADMIN #'.$user->id);
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        $this->createLog('RESTORE ADMIN #'.$user->id);
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        $this->createLog('FORCE DELETE ADMIN #'.$user->id);
    }

    protected function createLog(string $action)
    {
        if (Auth::check() && Auth::user()->hasRole('root')) {
            (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
        }
    }
}

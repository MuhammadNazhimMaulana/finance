<?php

namespace App\Observers;

use App\Models\Topup;
use App\Repositories\User\UserLogRepository;
use Illuminate\Support\Facades\Auth;

class TopupObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the topup "created" event.
     *
     * @param  \App\Models\Topup  $topup
     * @return void
     */
    public function created(Topup $topup)
    {
        $this->createLog('CREATE TOPUP #'.$topup->id);
    }

    protected function createLog(string $action)
    {
        if (Auth::check()) {
            (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
        }
    }
}

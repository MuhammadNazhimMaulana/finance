<?php

namespace App\Observers;

use App\Models\AccountCode;
use App\Repositories\User\UserLogRepository;

class AccountObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the account "created" event.
     *
     * @param  \App\Models\AccountCode  $account
     * @return void
     */
    public function created(AccountCode $account)
    {
        $this->createLog('CREATE ACCOUNT #'.$account->id);
    }

    /**
     * Handle the account "updated" event.
     *
     * @param  \App\Models\AccountCode  $account
     * @return void
     */
    public function updated(AccountCode $account)
    {
        $this->createLog('UPDATE ACCOUNT #'.$account->id);
    }

    /**
     * Handle the account "deleted" event.
     *
     * @param  \App\Models\AccountCode  $account
     * @return void
     */
    public function deleted(AccountCode $account)
    {
        $this->createLog('DELETE ACCOUNT #'.$account->id);
    }

    /**
     * Handle the account "restored" event.
     *
     * @param  \App\Models\AccountCode  $account
     * @return void
     */
    public function restored(AccountCode $account)
    {
        $this->createLog('RESTORE ACCOUNT #'.$account->id);
    }

    /**
     * Handle the account "force deleted" event.
     *
     * @param  \App\Models\AccountCode  $account
     * @return void
     */
    public function forceDeleted(AccountCode $account)
    {
        $this->createLog('FORCE DELETE ACCOUNT #'.$account->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

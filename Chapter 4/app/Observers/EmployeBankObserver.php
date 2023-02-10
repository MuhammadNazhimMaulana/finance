<?php

namespace App\Observers;

use App\Models\EmployeBank;
use App\Repositories\User\UserLogRepository;

class EmployeBankObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the employe bank "created" event.
     *
     * @param  \App\Models\EmployeBank  $employeBank
     * @return void
     */
    public function created(EmployeBank $employeBank)
    {
        $this->createLog('CREATE EMPLOYE BANK #'.$employeBank->id);
    }

    /**
     * Handle the employe bank "updated" event.
     *
     * @param  \App\Models\EmployeBank  $employeBank
     * @return void
     */
    public function updated(EmployeBank $employeBank)
    {
        $this->createLog('UPDATE EMPLOYE BANK #'.$employeBank->id);
    }

    /**
     * Handle the employe bank "deleted" event.
     *
     * @param  \App\Models\EmployeBank  $employeBank
     * @return void
     */
    public function deleted(EmployeBank $employeBank)
    {
        $this->createLog('DELETE EMPLOYE BANK #'.$employeBank->id);
    }

    /**
     * Handle the employe bank "restored" event.
     *
     * @param  \App\Models\EmployeBank  $employeBank
     * @return void
     */
    public function restored(EmployeBank $employeBank)
    {
        $this->createLog('RESTORE EMPLOYE BANK #'.$employeBank->id);
    }

    /**
     * Handle the employe bank "force deleted" event.
     *
     * @param  \App\Models\EmployeBank  $employeBank
     * @return void
     */
    public function forceDeleted(EmployeBank $employeBank)
    {
        $this->createLog('FORCE DELETE EMPLOYE BANK #'.$employeBank->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

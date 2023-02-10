<?php

namespace App\Observers;

use App\Models\Employe;
use App\Repositories\User\UserLogRepository;

class EmployeObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the employe "created" event.
     *
     * @param  \App\Models\Employe  $employe
     * @return void
     */
    public function created(Employe $employe)
    {
        $this->createLog('CREATE EMPLOYE #'.$employe->id);
    }

    /**
     * Handle the employe "updated" event.
     *
     * @param  \App\Models\Employe  $employe
     * @return void
     */
    public function updated(Employe $employe)
    {
        $this->createLog('UPDATE EMPLOYE #'.$employe->id);
    }

    /**
     * Handle the employe "deleted" event.
     *
     * @param  \App\Models\Employe  $employe
     * @return void
     */
    public function deleted(Employe $employe)
    {
        $this->createLog('DELETE EMPLOYE #'.$employe->id);
    }

    /**
     * Handle the employe "restored" event.
     *
     * @param  \App\Models\Employe  $employe
     * @return void
     */
    public function restored(Employe $employe)
    {
        $this->createLog('RESTORE EMPLOYE #'.$employe->id);
    }

    /**
     * Handle the employe "force deleted" event.
     *
     * @param  \App\Models\Employe  $employe
     * @return void
     */
    public function forceDeleted(Employe $employe)
    {
        $this->createLog('FORCE DELETE EMPLOYE #'.$employe->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

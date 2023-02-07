<?php

namespace App\Observers;

use App\Models\Department;
use App\Repositories\User\UserLogRepository;

class DepartmentObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the department "created" event.
     *
     * @param  \App\Models\Department  $department
     * @return void
     */
    public function created(Department $department)
    {
        $this->createLog('CREATE DEPARTMENT #'.$department->id);
    }

    /**
     * Handle the department "updated" event.
     *
     * @param  \App\Models\Department  $department
     * @return void
     */
    public function updated(Department $department)
    {
        $this->createLog('UPDATE DEPARTMENT #'.$department->id);
    }

    /**
     * Handle the department "deleted" event.
     *
     * @param  \App\Models\Department  $department
     * @return void
     */
    public function deleted(Department $department)
    {
        $this->createLog('DELETE DEPARTMENT #'.$department->id);
    }

    /**
     * Handle the department "restored" event.
     *
     * @param  \App\Models\Department  $department
     * @return void
     */
    public function restored(Department $department)
    {
        $this->createLog('RESTORE DEPARTMENT #'.$department->id);
    }

    /**
     * Handle the department "force deleted" event.
     *
     * @param  \App\Models\Department  $department
     * @return void
     */
    public function forceDeleted(Department $department)
    {
        $this->createLog('FORCE DELETE DEPARTMENT #'.$department->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

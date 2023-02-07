<?php

namespace App\Observers;

use App\Models\EmployeSalary;
use App\Repositories\User\UserLogRepository;
use Illuminate\Support\Facades\Auth;

class EmployeSalaryObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the employe salary "created" event.
     *
     * @param  \App\Models\EmployeSalary  $employeSalary
     * @return void
     */
    public function created(EmployeSalary $employeSalary)
    {
        $this->createLog('CREATE EMPLOYE SALARY #'.$employeSalary->id);
    }

    /**
     * Handle the employe salary "updated" event.
     *
     * @param  \App\Models\EmployeSalary  $employeSalary
     * @return void
     */
    public function updated(EmployeSalary $employeSalary)
    {
        $this->createLog('UPDATE EMPLOYE SALARY #'.$employeSalary->id);
    }

    /**
     * Handle the employe salary "deleted" event.
     *
     * @param  \App\Models\EmployeSalary  $employeSalary
     * @return void
     */
    public function deleted(EmployeSalary $employeSalary)
    {
        $this->createLog('DELETE EMPLOYE SALARY #'.$employeSalary->id);
    }

    /**
     * Handle the employe salary "restored" event.
     *
     * @param  \App\Models\EmployeSalary  $employeSalary
     * @return void
     */
    public function restored(EmployeSalary $employeSalary)
    {
        $this->createLog('RESTORE EMPLOYE SALARY #'.$employeSalary->id);
    }

    /**
     * Handle the employe salary "force deleted" event.
     *
     * @param  \App\Models\EmployeSalary  $employeSalary
     * @return void
     */
    public function forceDeleted(EmployeSalary $employeSalary)
    {
        $this->createLog('FORCE DELETE EMPLOYE SALARY #'.$employeSalary->id);
    }

    protected function createLog(string $action)
    {
        if (Auth::check()) {
            (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
        }
    }
}

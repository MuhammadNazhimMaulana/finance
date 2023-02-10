<?php

namespace App\Observers;

use App\Models\Branch;
use App\Repositories\User\UserLogRepository;

class BranchObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the branch "created" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function created(Branch $branch)
    {
        $this->createLog('CREATE BRANCH #'.$branch->id);
    }

    /**
     * Handle the branch "updated" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function updated(Branch $branch)
    {
        $this->createLog('UPDATE BRANCH #'.$branch->id);
    }

    /**
     * Handle the branch "deleted" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function deleted(Branch $branch)
    {
        $this->createLog('DELETE BRANCH #'.$branch->id);
    }

    /**
     * Handle the branch "restored" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function restored(Branch $branch)
    {
        $this->createLog('RESTORE BRANCH #'.$branch->id);
    }

    /**
     * Handle the branch "force deleted" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function forceDeleted(Branch $branch)
    {
        $this->createLog('FORCE DELETE BRANCH #'.$branch->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

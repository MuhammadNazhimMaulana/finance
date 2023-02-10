<?php

namespace App\Observers;

use App\Models\Position;
use App\Repositories\User\UserLogRepository;

class PositionObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the position "created" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function created(Position $position)
    {
        $this->createLog('CREATE POSITION #'.$position->id);
    }

    /**
     * Handle the position "updated" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function updated(Position $position)
    {
        $this->createLog('UPDATE POSITION #'.$position->id);
    }

    /**
     * Handle the position "deleted" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function deleted(Position $position)
    {
        $this->createLog('DELETE POSITION #'.$position->id);
    }

    /**
     * Handle the position "restored" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function restored(Position $position)
    {
        $this->createLog('RESTORE POSITION #'.$position->id);
    }

    /**
     * Handle the position "force deleted" event.
     *
     * @param  \App\Position  $position
     * @return void
     */
    public function forceDeleted(Position $position)
    {
        $this->createLog('FORCE DELETE POSITION #'.$position->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

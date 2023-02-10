<?php

namespace App\Observers;

use App\Models\Disbursement;
use App\Repositories\User\UserLogRepository;
use Illuminate\Support\Facades\Auth;

class DisbursementObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the disbursement "created" event.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return void
     */
    public function created(Disbursement $disbursement)
    {
        $this->createLog('CREATE DISBURSEMENT #'.$disbursement->id);
    }

    /**
     * Handle the disbursement "updated" event.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return void
     */
    public function updated(Disbursement $disbursement)
    {
        $this->createLog('UPDATE DISBURSEMENT #'.$disbursement->id);
    }

    /**
     * Handle the disbursement "deleted" event.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return void
     */
    public function deleted(Disbursement $disbursement)
    {
        $this->createLog('DELETE DISBURSEMENT #'.$disbursement->id);
    }

    /**
     * Handle the disbursement "restored" event.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return void
     */
    public function restored(Disbursement $disbursement)
    {
        $this->createLog('RESTORE DISBURSEMENT #'.$disbursement->id);
    }

    /**
     * Handle the disbursement "force deleted" event.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return void
     */
    public function forceDeleted(Disbursement $disbursement)
    {
        $this->createLog('FORCE DELETE DISBURSEMENT #'.$disbursement->id);
    }

    protected function createLog(string $action)
    {
        if (Auth::check()) {
            (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
        }
    }
}

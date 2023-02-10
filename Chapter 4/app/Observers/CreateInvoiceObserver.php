<?php

namespace App\Observers;

use App\Models\ManualInvoice;
use App\Repositories\User\UserLogRepository;

class CreateInvoiceObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

        /**
     * Handle the manualInvoice "created" event.
     *
     * @param  \App\Models\ManualInvoice  $invoiceContact
     * @return void
     */
    public function created(ManualInvoice $manualInvoice)
    {
        $this->createLog('CREATE INVOICE #'.$manualInvoice->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

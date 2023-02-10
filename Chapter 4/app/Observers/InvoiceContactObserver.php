<?php

namespace App\Observers;

use App\Models\InvoiceContact;
use App\Repositories\User\UserLogRepository;

class InvoiceContactObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

        /**
     * Handle the invoiceContact "created" event.
     *
     * @param  \App\Models\InvoiceContact  $invoiceContact
     * @return void
     */
    public function created(InvoiceContact $invoiceContact)
    {
        $this->createLog('CREATE INVOICE CONTACT #'.$invoiceContact->id);
    }

    /**
     * Handle the invoiceContact "updated" event.
     *
     * @param  \App\Models\InvoiceContact  $invoiceContact
     * @return void
     */
    public function updated(InvoiceContact $invoiceContact)
    {
        $this->createLog('UPDATE INVOICE CONTACT #'.$invoiceContact->id);
    }

    /**
     * Handle the invoiceContact "deleted" event.
     *
     * @param  \App\Models\InvoiceContact  $invoiceContact
     * @return void
     */
    public function deleted(InvoiceContact $invoiceContact)
    {
        $this->createLog('DELETE INVOICE CONTACT #'.$invoiceContact->id);
    }

    /**
     * Handle the invoiceContact "restored" event.
     *
     * @param  \App\Models\InvoiceContact  $invoiceContact
     * @return void
     */
    public function restored(InvoiceContact $invoiceContact)
    {
        $this->createLog('RESTORE INVOICE CONTACT #'.$invoiceContact->id);
    }

    /**
     * Handle the invoiceContact "force deleted" event.
     *
     * @param  \App\Models\InvoiceContact  $invoiceContact
     * @return void
     */
    public function forceDeleted(InvoiceContact $invoiceContact)
    {
        $this->createLog('FORCE DELETE INVOICE CONTACT #'.$invoiceContact->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

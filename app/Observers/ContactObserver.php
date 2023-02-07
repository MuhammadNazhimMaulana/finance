<?php

namespace App\Observers;

use App\Models\Contact;
use App\Repositories\User\UserLogRepository;

class ContactObserver
{
    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    /**
     * Handle the contact "created" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function created(Contact $contact)
    {
        $this->createLog('CREATE CONTACT #'.$contact->id);
    }

    /**
     * Handle the contact "updated" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function updated(Contact $contact)
    {
        $this->createLog('UPDATE CONTACT #'.$contact->id);
    }

    /**
     * Handle the contact "deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function deleted(Contact $contact)
    {
        $this->createLog('DELETE CONTACT #'.$contact->id);
    }

    /**
     * Handle the contact "restored" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function restored(Contact $contact)
    {
        $this->createLog('RESTORE CONTACT #'.$contact->id);
    }

    /**
     * Handle the contact "force deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function forceDeleted(Contact $contact)
    {
        $this->createLog('FORCE DELETE CONTACT #'.$contact->id);
    }

    protected function createLog(string $action)
    {
        (new UserLogRepository)->store($this->user->id, $action, $this->request->ip(), $this->request->header('user-agent'));
    }
}

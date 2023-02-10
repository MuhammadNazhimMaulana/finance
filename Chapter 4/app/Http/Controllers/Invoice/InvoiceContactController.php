<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Interfaces\Invoice\InvoiceContactInterface;
use App\Http\Requests\Invoice\RequestInvoiceContact;

class InvoiceContactController extends Controller
{
    public function __construct(InvoiceContactInterface $invoiceContactInterface)
    {
        $this->invoiceContactInterface = $invoiceContactInterface;
    }

    public function contact()
    {
        return $this->invoiceContactInterface->contact();
    }

    public function index()
    {
        return $this->invoiceContactInterface->index();
    }

    public function store(RequestInvoiceContact $request)
    {
        return $this->invoiceContactInterface->store($request);
    }

    public function update(int $id, RequestInvoiceContact $request)
    {
        return $this->invoiceContactInterface->update($id, $request);
    }

    public function destroy(int $id)
    {
        return $this->invoiceContactInterface->destroy($id);
    }
}

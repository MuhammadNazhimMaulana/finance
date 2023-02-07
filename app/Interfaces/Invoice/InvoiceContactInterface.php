<?php

namespace App\Interfaces\Invoice;
use App\Http\Requests\Invoice\RequestInvoiceContact;

interface InvoiceContactInterface
{
    public function contact();

    public function index();

    public function store(RequestInvoiceContact $request);

    public function update(int $id, RequestInvoiceContact $request);

    public function destroy(int $id);
}

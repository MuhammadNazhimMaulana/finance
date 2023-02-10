<?php

namespace App\Interfaces\Invoice;
use App\Http\Requests\Invoice\CreateInvoiceRequest;

interface CreateInvoiceInterface
{
    public function index();

    public function store(CreateInvoiceRequest $request);
}

<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Interfaces\Invoice\CreateInvoiceInterface;
use App\Http\Requests\Invoice\CreateInvoiceRequest;

class CreateInvoiceController extends Controller
{
    public function __construct(CreateInvoiceInterface $createInvoiceInterface)
    {
        $this->createInvoiceInterface = $createInvoiceInterface;
    }

    public function index()
    {
        return $this->createInvoiceInterface->index();
    }

    public function store(CreateInvoiceRequest $request)
    {
        return $this->createInvoiceInterface->store($request);
    }

}

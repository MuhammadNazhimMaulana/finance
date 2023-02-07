<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Interfaces\Invoice\HistoryInvoiceInterface;

class HistoryInvoiceController extends Controller
{
    public function __construct(HistoryInvoiceInterface $historyInvoiceInterface)
    {
        $this->historyInvoiceInterface = $historyInvoiceInterface;
    }

    public function index()
    {
        return $this->historyInvoiceInterface->index();
    }

}

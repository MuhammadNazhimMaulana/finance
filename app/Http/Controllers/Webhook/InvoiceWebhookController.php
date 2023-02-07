<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Interfaces\Webhook\InvoiceWebhookInterface;
use Illuminate\Http\Request;

class InvoiceWebhookController extends Controller
{
    public function __construct(InvoiceWebhookInterface $invoiceWebhookInterface)
    {
        $this->invoiceWebhookInterface = $invoiceWebhookInterface;
    }

    public function index()
    {
        return $this->invoiceWebhookInterface->index();
    }
}

<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Interfaces\Webhook\DonationWebhookInterface;
use Illuminate\Http\Request;

class DonationWebhookController extends Controller
{
    public function __construct(DonationWebhookInterface $donationWebhookInterface)
    {
        $this->donationWebhookInterface = $donationWebhookInterface;
    }

    public function index()
    {
        return $this->donationWebhookInterface->index();
    }

    public function store(Request $request)
    {
        return $this->donationWebhookInterface->store($request);
    }
}

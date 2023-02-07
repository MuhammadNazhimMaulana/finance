<?php

namespace App\Interfaces\Webhook;

interface DonationWebhookInterface
{
    public function index();

    public function store($request);
}

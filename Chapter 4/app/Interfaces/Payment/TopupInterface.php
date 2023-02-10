<?php

namespace App\Interfaces\Payment;

use App\Http\Requests\Payment\Topup\StoreRequest;

interface TopupInterface
{
    public function store(StoreRequest $request);
}

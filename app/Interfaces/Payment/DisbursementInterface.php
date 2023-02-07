<?php

namespace App\Interfaces\Payment;

use App\Http\Requests\Payment\Disbursement\StoreRequest;

interface DisbursementInterface
{
    public function store(StoreRequest $request);
}

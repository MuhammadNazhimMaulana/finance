<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Interfaces\Payment\DisbursementInterface;
use App\Http\Requests\Payment\Disbursement\StoreRequest;

class DisbursementController extends Controller
{
    public function __construct(DisbursementInterface $disbursementInterface)
    {
        $this->disbursementInterface = $disbursementInterface;
    }

    public function store(StoreRequest $request)
    {
        return $this->disbursementInterface->store($request);
    }
}

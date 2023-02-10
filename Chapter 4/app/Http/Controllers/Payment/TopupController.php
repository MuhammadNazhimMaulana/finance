<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Interfaces\Payment\TopupInterface;
use App\Http\Requests\Payment\Topup\StoreRequest;

class TopupController extends Controller
{
    public function __construct(TopupInterface $topupInterface)
    {
        $this->topupInterface = $topupInterface;
    }

    public function store(StoreRequest $request)
    {
        return $this->topupInterface->store($request);
    }
}

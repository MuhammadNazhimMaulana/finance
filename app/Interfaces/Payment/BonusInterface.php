<?php

namespace App\Interfaces\Payment;

use App\Http\Requests\Payment\Salary\FilterRequest;
use Illuminate\Http\Request;

interface BonusInterface
{
    public function index(FilterRequest $request);

    public function storeBonusConfig(Request $request);
}

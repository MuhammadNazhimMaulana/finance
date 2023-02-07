<?php

namespace App\Interfaces\Report;

use App\Http\Requests\Payment\Topup\FilterRequest;

interface TopupReportInterface
{
    public function index(FilterRequest $request);
}

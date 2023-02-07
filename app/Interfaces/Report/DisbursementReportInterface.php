<?php

namespace App\Interfaces\Report;

use App\Http\Requests\Payment\Disbursement\FilterRequest;

interface DisbursementReportInterface
{
    public function index(string $category, FilterRequest $request);
}

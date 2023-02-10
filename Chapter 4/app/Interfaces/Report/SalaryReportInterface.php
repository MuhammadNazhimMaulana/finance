<?php

namespace App\Interfaces\Report;

use App\Http\Requests\Payment\Salary\FilterRequest;

interface SalaryReportInterface
{
    public function index(FilterRequest $request);
}

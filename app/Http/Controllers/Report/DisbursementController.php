<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Interfaces\Report\DisbursementReportInterface;
use App\Http\Requests\Payment\Disbursement\FilterRequest;

class DisbursementController extends Controller
{
    public function __construct(DisbursementReportInterface $disbursementReportInterface)
    {
        $this->disbursementReportInterface = $disbursementReportInterface;
    }

    public function index(string $category, FilterRequest $request)
    {
        return $this->disbursementReportInterface->index($category, $request);
    }
}

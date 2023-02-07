<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Interfaces\Report\ExportReportInterface;
use App\Http\Requests\Report\StoreRequest;

class ExportController extends Controller
{
    public function __construct(ExportReportInterface $exportReportInterface)
    {
        $this->exportReportInterface = $exportReportInterface;
    }

    public function index()
    {
        return $this->exportReportInterface->index();
    }

    public function store(StoreRequest $request)
    {
        return $this->exportReportInterface->store($request);
    }
}

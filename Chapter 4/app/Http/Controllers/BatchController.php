<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\BatchInterface;
use App\Http\Requests\Payment\Batch\StoreRequest;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function __construct(BatchInterface $batchInterface)
    {
        $this->batchInterface = $batchInterface;
    }

    public function index()
    {
        return $this->batchInterface->index();
    }
    
    public function store(StoreRequest $request)
    {
        return $this->batchInterface->store($request);
    }

    public function retransfer(Request $request)
    {
        return $this->batchInterface->retransfer($request);
    }

    public function transferProof(int $disbursement_id)
    {
        return $this->batchInterface->transferProof($disbursement_id);
    }
}

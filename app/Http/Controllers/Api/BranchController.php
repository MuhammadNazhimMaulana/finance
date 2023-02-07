<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\Api\BranchInterface;

class BranchController extends Controller
{
    public function __construct(BranchInterface $branchInterface)
    {
        $this->branchInterface = $branchInterface;
    }

    public function index()
    {
        return $this->branchInterface->index();
    }
}

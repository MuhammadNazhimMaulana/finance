<?php

namespace App\Http\Controllers;

use App\Interfaces\FeeRuleInterface;
use Illuminate\Http\Request;

class FeeRuleController extends Controller
{
    public function __construct(FeeRuleInterface $feeRuleInterface)
    {
        $this->feeRuleInterface = $feeRuleInterface;
    }

    public function index()
    {
        return $this->feeRuleInterface->index();
    }

    public function store(Request $request)
    {
        return $this->feeRuleInterface->store($request);
    }


    public function destroy(int $id)
    {
        return $this->feeRuleInterface->destroy($id);
    }
}

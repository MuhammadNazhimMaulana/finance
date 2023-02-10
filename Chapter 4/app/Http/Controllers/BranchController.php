<?php

namespace App\Http\Controllers;

use App\Interfaces\BranchInterface;
use App\Http\Requests\BranchRequest;

class BranchController extends Controller
{
    public function __construct(BranchInterface $branchInterface)
    {
        $this->branchInterface = $branchInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->branchInterface->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\BranchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRequest $request)
    {
        return $this->branchInterface->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\BranchRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, int $id)
    {
        return $this->branchInterface->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->branchInterface->destroy($id);
    }
}

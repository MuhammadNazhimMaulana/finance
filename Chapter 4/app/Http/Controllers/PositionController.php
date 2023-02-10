<?php

namespace App\Http\Controllers;

use App\Interfaces\PositionInterface;
use App\Http\Requests\PositionRequest;

class PositionController extends Controller
{
    public function __construct(PositionInterface $positionInterface)
    {
        $this->positionInterface = $positionInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->positionInterface->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\PositionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionRequest $request)
    {
        return $this->positionInterface->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\PositionRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PositionRequest $request, int $id)
    {
        return $this->positionInterface->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->positionInterface->destroy($id);
    }
}

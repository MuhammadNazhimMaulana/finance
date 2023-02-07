<?php

namespace App\Repositories\Api;;

use App\Interfaces\Api\BranchInterface;
use App\Traits\BugsnagTrait;
use App\Models\Branch;
use App\Http\Resources\Api\BranchResource;
use Throwable;

class BranchRepository implements BranchInterface
{
    use BugsnagTrait;

    public function index()
    {
        try {
            $request = request();
            if ($request->filled('q') && strlen($request->q) >= 3) {
                $q = strip_tags($request->q);
                $res = Branch::where('name', 'ilike', "%$q%")->take(10)->get();

                $data = BranchResource::collection($res);

                return response()->json(array('results' => $data));
            }

            return response()->json([]);
        } catch (Throwable $e) {
            $this->report($e);
            return response()->json([], 400);
        }
    }
}

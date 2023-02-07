<?php

namespace App\Repositories;;

use App\Interfaces\BranchInterface;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Throwable;

class BranchRepository implements BranchInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            $request = request();

            $res = Branch::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'ilike', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            return view('branch.index', [
                'data' => $data
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(BranchRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = new Branch;
            $res->name = $request->name;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Branch Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, BranchRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = Branch::findOrFail($id);
            $res->name = $request->name;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Branch Updated'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $res = Branch::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Branch Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}

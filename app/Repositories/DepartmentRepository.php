<?php

namespace App\Repositories;;

use App\Interfaces\DepartmentInterface;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Throwable;

class DepartmentRepository implements DepartmentInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            $request = request();

            $res = Department::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'ilike', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            return view('department.index', [
                'data' => $data
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(DepartmentRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = new Department;
            $res->name = $request->name;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Department Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, DepartmentRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = Department::findOrFail($id);
            $res->name = $request->name;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Department Updated'));
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
            $res = Department::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Department Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}

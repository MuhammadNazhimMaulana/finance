<?php

namespace App\Repositories;;

use App\Interfaces\PositionInterface;
use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Throwable;

class PositionRepository implements PositionInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            $request = request();

            $res = Position::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'ilike', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            return view('position.index', [
                'data' => $data
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(PositionRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = new Position;
            $res->name = $request->name;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Position Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, PositionRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = Position::findOrFail($id);
            $res->name = $request->name;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Position Updated'));
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
            $res = Position::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Position Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}

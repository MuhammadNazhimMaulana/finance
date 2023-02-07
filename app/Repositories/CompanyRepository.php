<?php

namespace App\Repositories;;

use App\Interfaces\CompanyInterface;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Throwable;

class CompanyRepository implements CompanyInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            $request = request();

            $res = Company::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'ilike', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            return view('company.index', [
                'data' => $data
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(CompanyRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = new Company;
            $res->name = $request->name;
            $res->email = $request->email;
            $res->address = $request->address;
            $res->phone = $request->phone;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Company Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, CompanyRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = Company::findOrFail($id);
            $res->name = $request->name;
            $res->email = $request->email;
            $res->address = $request->address;
            $res->phone = $request->phone;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Company Updated'));
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
            $res = Company::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Company Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}

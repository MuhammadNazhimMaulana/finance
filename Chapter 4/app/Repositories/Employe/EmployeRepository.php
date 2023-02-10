<?php

namespace App\Repositories\Employe;

use App\Interfaces\Employe\EmployeInterface;
use App\Http\Requests\Employe\EmployeRequest;
use App\Models\{Employe,Company, Department, Position};
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Throwable;

class EmployeRepository implements EmployeInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            $request = request();

            $res = Employe::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'ilike', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            $companies = Company::all();
            $departments = Department::all();
            $positions = Position::all();

            return view('employe.index', [
                'data' => $data,
                'companies' => $companies,
                'departments' => $departments,
                'positions' => $positions
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(EmployeRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = new Employe;
            $res->company_id = $request->company_id;
            $res->branch_id = $request->branch_id;
            $res->department_id = $request->department_id;
            $res->position_id = $request->position_id;
            $res->name = $request->name;
            $res->email = $request->email;
            $res->monthly_salary = (int) str_replace('.', '', $request->monthly_salary);
            if ($request->filled('nik')) {
                $res->nik = $request->nik;
            }
            if ($request->filled('address')) {
                $res->address = $request->address;
            }
            if ($request->filled('date_of_birth')) {
                $res->date_of_birth = $request->date_of_birth;
            }
            if ($request->filled('place_of_birth')) {
                $res->place_of_birth = $request->place_of_birth;
            }
            if ($request->filled('phone')) {
                $res->phone = $request->phone;
            }
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Employe Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, EmployeRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = Employe::findOrFail($id);
            $res->company_id = $request->company_id;
            $res->branch_id = $request->branch_id;
            $res->department_id = $request->department_id;
            $res->position_id = $request->position_id;
            $res->name = $request->name;
            $res->email = $request->email;
            $res->monthly_salary = (int) str_replace('.', '', $request->monthly_salary);
            if ($request->filled('nik')) {
                $res->nik = $request->nik;
            }
            if ($request->filled('address')) {
                $res->address = $request->address;
            }
            if ($request->filled('date_of_birth')) {
                $res->date_of_birth = $request->date_of_birth;
            }
            if ($request->filled('place_of_birth')) {
                $res->place_of_birth = $request->place_of_birth;
            }
            if ($request->filled('phone')) {
                $res->phone = $request->phone;
            }
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Employe Updated'));
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
            $res = Employe::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Employe Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}

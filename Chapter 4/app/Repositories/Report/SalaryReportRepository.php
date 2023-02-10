<?php

namespace App\Repositories\Report;;

use App\Interfaces\Report\SalaryReportInterface;
use App\Http\Requests\Payment\Salary\FilterRequest;
use App\Models\{EmployeSalary, Branch, Company, Department, Position};
use Carbon\Carbon;
use App\Traits\BugsnagTrait;
use Throwable;

class SalaryReportRepository implements SalaryReportInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index(FilterRequest $request)
    {
        try {
            $defaultYear = Carbon::now()->format('Y');
            if ($request->filled('date')) {
                $defaultYear = Carbon::parse($request->date)->format('Y');
            }
            $defaultMonth = Carbon::now()->format('m');
            if ($request->filled('date')) {
                $defaultYear = Carbon::parse($request->date)->format('m');
            }
            $branchId = null;
            $branchName = null;

            $query = EmployeSalary::query();
            $query->ofSalary($defaultMonth, $defaultYear);
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $query->whereHas('employe', function($q) use ($name) {
                    $q->where('name', 'ilike', "%$name%");
                });
            }
            if ($request->filled('company_id')) {
                $query->ofCompany($request->company_id);
            }
            if ($request->filled('branch_id')) {
                $branchQuery = Branch::findOrFail($request->branch_id);
                $branchId = $branchQuery->id;
                $branchName = $branchQuery->name;
                $query->ofBranch($request->branch_id);
            }
            if ($request->filled('department_id')) {
                $query->ofDepartment($request->department_id);
            }
            if ($request->filled('position_id')) {
                $query->ofPosition($request->position_id);
            }
            if ($request->filled('status')) {
                $query->ofStatus($request->status);
            }
            if ($request->filled('date')) {
                $query->whereYear('created_at', $defaultYear)->whereMonth('created_at', $defaultMonth);
            }

            $data = $query->paginate(self::PER_PAGE);

            $totalSalary = $query->where('status', EmployeSalary::STATUS_COMPLETED)->sum('amount');

            $companies = Company::all();
            $departments = Department::all();
            $positions = Position::all();

            return view('report.salary.index', [
                'data' => $data,
                'companies' => $companies,
                'departments' => $departments,
                'positions' => $positions,
                'totalSalary' => $totalSalary,
                'branchId' => $branchId,
                'branchName' => $branchName
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }
}

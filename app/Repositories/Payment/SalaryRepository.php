<?php

namespace App\Repositories\Payment;;

use App\Http\Requests\Payment\Salary\{FilterRequest, StoreRequest};
use App\Interfaces\Payment\SalaryInterface;
use Illuminate\Http\Request;
use App\Traits\BugsnagTrait;
use App\Models\{Employe, Company, Department, Branch, EmployeBank, Position, EmployeSalary, Config};
use App\Repositories\XenditRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;
use Throwable;

class SalaryRepository implements SalaryInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;
    const WITHDRAW_CODE = 'SALARY';

    public function index(FilterRequest $request)
    {
        try {
            $defaultYear = Carbon::now()->format('Y');
            $defaultMonth = Carbon::now()->format('m');
            $branchId = null;
            $branchName = null;

            // Find Config
            $config = Config::where('name', Config::SALARY_PAYMENT_DATE)->first();

            $query = Employe::query();
            $query->ofSalary($defaultMonth, $defaultYear);
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $query->where('name', 'ilike', "%{$name}%");
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

            $totalSalary = $query->sum('monthly_salary');

            $data = $query->paginate(self::PER_PAGE);

            $companies = Company::all();
            $departments = Department::all();
            $positions = Position::all();

            return view('payment.salary.index', [
                'data' => $data,
                'config' => $config ? Carbon::createFromFormat('d', $config->value) : $config,
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

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $admin = $request->user();

            $defaultYear = Carbon::now()->format('Y');
            $defaultMonth = Carbon::now()->format('m');

            // Validate Pin
            if (!$admin->pin) return back()->with('errorMsg', 'PIN Anda Salah!');
            if (!Hash::check($request->pin, $admin->pin->value)) return back()->with('errorMsg', 'PIN Anda Salah!');

            // Check if resend transfer
            if ($request->filled('employe_salary_id')) {
                $resendTransfer = EmployeSalary::find($request->employe_salary_id);
                if (!$resendTransfer) return back()->with('errorMsg', 'Data tidak ditemukan');
                if ($resendTransfer->status === EmployeSalary::STATUS_COMPLETED) return back()->with('errorMsg', 'Sudah di transfer sebelumnya!');
            }

            // Validate Employe
            $employe = isset($resendTransfer) ? Employe::find($resendTransfer->employe_id) : Employe::find($request->employe_id);
            if (!$employe) return back()->with('errorMsg', 'Employe cannot be found');

            // Validate Employe Bank
            $employeBank = EmployeBank::find($request->employe_bank_id);
            if (!$employeBank) return back()->with('errorMsg', 'Employe bank cannot be found');
            if ($employeBank->employe_id !== $employe->id) return back()->with('errorMsg', 'Invalid employe bank');

            // Validate Employe Salary For This Month
            $alreadyPaidPaySalary = EmployeSalary::where('employe_id', $employe->id)->whereYear('salary_date', $defaultYear)->whereMonth('salary_date', $defaultMonth)->where('status', EmployeSalary::STATUS_COMPLETED)->first();
            if ($alreadyPaidPaySalary) return back()->with('errorMsg', $employe->name.' Sudah di transfer sebelumnya!');

            // Disbursement
            $tryCount = 1;
            if ($request->filled('try_count') && $request->try_count > 1) {
                $tryCount = $request->try_count;
            }
            $externalId = self::WITHDRAW_CODE.'-'.$employe->id.'-T'.$tryCount.'-'.Carbon::now()->format('Y-m').'-'.md5(request()->url() . Carbon::now());

            // Check duplicate tansaction
            $chk = EmployeSalary::where('external_id', $externalId)->first();
            if ($chk) return back()->with('errorMsg', $employe->name.' Sudah di transfer sebelumnya!');

            $amount = isset($resendTransfer) ? (int)$resendTransfer->amount : (int)$employe->monthly_salary;
            $amount = $amount - env('CASH_OUT_TOTAL_FEE');

            $description = strtoupper('salary '.$employe->name.' bulan '.Carbon::now()->format('F').' '.Carbon::now()->format('Y').' Rp'.CurrencyHelper::toIDR($employe->monthly_salary).' ke '.$employeBank->name.' no rek '.$employeBank->account_number.' atas nama '.$employeBank->account_holder_name).' diproses oleh '.$admin->name;
            $params = [
                'json' => [
                    'account_id' => env('XENDIT_ACCOUNT_ID'),
                    'external_id' => $externalId,
                    'bank_code' => $employeBank->code,
                    'account_holder_name' => $employeBank->account_holder_name,
                    'account_number' => $employeBank->account_number,
                    'description' => $description,
                    'amount' => $amount,
                    'email_to' => [$employe->email],
                    'meta_data' => json_decode(json_encode($admin), true)
                ]
            ];

            $res = XenditRepository::createDisbursement($params);
            if (!isset($res->data->id)) return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway');
            if ($res->data->status === 'FAILED') return back()->with('errorMsg', 'Bank tujuan tidak ditemukan');

            $employe->company;
            $employe->branch;
            $employe->department;
            $employe->position;

            $insert = isset($resendTransfer) ? $resendTransfer : new EmployeSalary;
            $insert->employe_id = $employe->id;
            $insert->employe_bank_id = $employeBank->id;
            $insert->external_id = $externalId;
            $insert->xendit_id = $res->data->xendit_id;
            $insert->status = $res->data->status;
            $insert->salary_date = Carbon::now()->format('Y-m-d');
            $insert->amount = $amount;
            $insert->fee = env('CASH_OUT_TOTAL_FEE');
            $insert->total = $amount + env('CASH_OUT_TOTAL_FEE');
            $insert->description = $description;
            $insert->try_count = $tryCount;
            $insert->xendit_data = json_decode(json_encode($res), true);
            $insert->employe_data = json_decode(json_encode($employe), true);
            $insert->employe_bank_data = json_decode(json_encode($employeBank), true);
            $insert->transferred_by = json_decode(json_encode($admin), true);
            $insert->save();

            DB::commit();

            return back()->with('status', 'Tansfer ke '.$employe->name.' sedang diproses');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway');
        }
    }

    public function storeDate(Request $request)
    {
        DB::beginTransaction();
        try {

            // Find Config
            $conf = Config::where('name', Config::SALARY_PAYMENT_DATE)->first();

            if(!$conf){
                // Create Config
                $config = new Config;
                $config->name = Config::SALARY_PAYMENT_DATE;
                $config->value = $request->payment_date;
                $config->save();
            }else{
                // Update if conf exist
                $conf->value = $request->payment_date;
                $conf->save();
            }

            DB::commit();

            return back()->with('status', 'Set up tanggal pembayaran berhasil');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway'. $e);
        }
    }
}

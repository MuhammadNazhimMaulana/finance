<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\BugsnagTrait;
use App\Models\{Employe, EmployeBank, EmployeSalary, Config};
use App\Repositories\XenditRepository;
use App\Helpers\CurrencyHelper;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class SalarySchedule extends Command
{
    use BugsnagTrait;
    const WITHDRAW_CODE = 'SALARY';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Salary Schedule';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Today's date
        $now = Carbon::now()->format('d');

        // Config's date
        $config = Config::where('name', Config::SALARY_PAYMENT_DATE)->first();

        if($now == $config->value)
        {
            $datas = Employe::withoutGlobalScopes()->orderBy('created_at', 'asc')->whereHas('employebanks')->cursor();
            foreach($datas as $data)
            {
                DB::beginTransaction();
                try {
                    $defaultYear = Carbon::now()->format('Y');
                    $defaultMonth = Carbon::now()->format('m');
                    
                    $admin = User::find(1);
    
                    // Validate Employe Bank
                    $employeBank = EmployeBank::where('employe_id', $data->id)->where('status', EmployeBank::STATUS_ACTIVE)->first();
                    if (!$employeBank) continue;
                    if ($employeBank->employe_id !== $data->id) continue;
        
                    // Validate Employe Salary For This Month
                    $alreadyPaidPaySalary = EmployeSalary::where('employe_id', $data->id)->whereYear('salary_date', $defaultYear)->whereMonth('salary_date', $defaultMonth)->where('status', EmployeSalary::STATUS_COMPLETED)->first();
                    if ($alreadyPaidPaySalary) continue;
    
                    // Disbursement
                    $tryCount = 1;
                    $externalId = self::WITHDRAW_CODE.'-'.$data->id.'-T'.$tryCount.'-'.Carbon::now()->format('Y-m').'-'.md5(request()->url());
    
                    // Check duplicate tansaction
                    $chk = EmployeSalary::where('external_id', $externalId)->first();
                    if ($chk) continue;
        
                    $amount = (int)$data->monthly_salary;
                    $amount = $amount - env('CASH_OUT_TOTAL_FEE');
        
                    $description = strtoupper('salary '.$data->name.' bulan '.Carbon::now()->format('F').' '.Carbon::now()->format('Y').' Rp'.CurrencyHelper::toIDR($data->monthly_salary).' ke '.$employeBank->name.' no rek '.$employeBank->account_number.' atas nama '.$employeBank->account_holder_name).' diproses oleh '.$admin->name;
                    $params = [
                        'json' => [
                            'account_id' => env('XENDIT_ACCOUNT_ID'),
                            'external_id' => $externalId,
                            'bank_code' => $employeBank->code,
                            'account_holder_name' => $employeBank->account_holder_name,
                            'account_number' => $employeBank->account_number,
                            'description' => $description,
                            'amount' => $amount,
                            'email_to' => [$data->email],
                            'meta_data' => json_decode(json_encode($admin), true)
                        ]
                    ];
    
                    $res = XenditRepository::createDisbursement($params);
                    if (!isset($res->data->id)) continue;
                    if ($res->data->status === 'FAILED') continue;
    
                    $data->company;
                    $data->branch;
                    $data->department;
                    $data->position;
        
                    $insert = new EmployeSalary;
                    $insert->employe_id = $data->id;
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
                    $insert->employe_data = json_decode(json_encode($data), true);
                    $insert->employe_bank_data = json_decode(json_encode($employeBank), true);
                    $insert->transferred_by = json_decode(json_encode($admin), true);
                    $insert->save();
    
                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollback();
                    $this->report($e);
                    continue;
                }
            }
        }
    }
}

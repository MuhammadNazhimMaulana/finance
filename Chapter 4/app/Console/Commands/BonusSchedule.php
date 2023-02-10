<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\BugsnagTrait;
use App\Models\{Employe, EmployeBank, Disbursement, Config};
use Carbon\Carbon;
use App\Repositories\XenditRepository;
use App\Helpers\CurrencyHelper;
use Illuminate\Support\Facades\DB;
use App\User;
use Throwable;

class BonusSchedule extends Command
{
    use BugsnagTrait;

    private $disbursementInterface;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bonus Schedule';

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
        $type = Config::where('name', Config::BONUS_TYPE)->first();

        if($type->value == Config::TAHUNAN)
        {
            // Today's date
            $now = Carbon::now()->format('m-d');
        }else if($type == Config::BULANAN)
        {
            // Today's date
            $now = Carbon::now()->format('d');
        }

        // Config's date
        $config = Config::where('name', Config::BONUS_PAYMENT_DATE)->first();

        // Config's percentage
        $percentage = Config::where('name', Config::BONUS_PAYMENT_PERCENTAGE)->first();
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

                    $category = strtoupper(Disbursement::BONUS);
                    $todayDate = Carbon::now()->format('Y-m-d');
                    $amount = (int) str_replace('.', '', ($data->monthly_salary * $percentage->value) / 100);
                    $hash = md5($amount.$employeBank->code.$employeBank->account_holder_name.$employeBank->account_number.$todayDate.request()->url());
        
                    $externalId = $category.'-'.$amount.'-'.$hash;
        
                    // Check duplicate request
                    $chk = Disbursement::where('external_id', $externalId)->first();
                    if ($chk) {
                        if ($chk->status === Disbursement::STATUS_FAILED) {
                            $externalId = $externalId.time();
                        } else {
                            continue;
                        }
                    }

                    $amount = $amount - env('CASH_OUT_TOTAL_FEE');

                    $description = strip_tags("Pembayaran Bonus");

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
          
                    $insert = new Disbursement;
                    $insert->category = $category;
                    $insert->external_id = $externalId;
                    $insert->xendit_id = $res->data->xendit_id;
                    $insert->status = $res->data->status;
                    $insert->amount = $amount;
                    $insert->fee = env('CASH_OUT_TOTAL_FEE');
                    $insert->total = $amount + env('CASH_OUT_TOTAL_FEE');
                    $insert->to_name = $data->name;
                    $insert->to_email = $data->email;
                    $insert->bank_code = $employeBank->code;
                    $insert->bank_name = $employeBank->name;
                    $insert->bank_account_holder_name = $employeBank->account_holder_name;
                    $insert->bank_account_number = $employeBank->account_number;
                    $insert->description = $description;
                    $insert->xendit_data = json_decode(json_encode($res), true);
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

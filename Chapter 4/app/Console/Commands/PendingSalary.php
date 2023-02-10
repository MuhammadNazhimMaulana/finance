<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\BugsnagTrait;
use App\Models\EmployeSalary;
use App\Repositories\XenditRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class PendingSalary extends Command
{
    use BugsnagTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending:salary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Pending Salary';

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
        $datas = EmployeSalary::withoutGlobalScopes()->orderBy('created_at', 'asc')->where('status', EmployeSalary::STATUS_PENDING)->take(10)->get();
        foreach ($datas as $data) {
            DB::beginTransaction();
            try {
                $res = XenditRepository::getDisbursement($data->xendit_id);
                if (isset($res->data->status) && $res->data->status !== EmployeSalary::STATUS_PENDING) {
                    $data->xendit_data = json_decode(json_encode($res), true);
                    $data->status = $res->data->status;
                    $data->save();
                }
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
                $this->report($e);
                continue;
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\BugsnagTrait;
use App\Models\{Donation, ManualInvoice};
use App\Repositories\XenditRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckDonationCommand extends Command
{
    use BugsnagTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checking:donation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking Donation';

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
        $datas = Donation::withoutGlobalScopes()->orderBy('created_at', 'asc')->where('status', ManualInvoice::STATUS_PENDING)->take(10)->get();
        foreach ($datas as $data) {
            DB::beginTransaction();
            try {
                $res = XenditRepository::getInvoice($data->xendit_id);
                if (isset($res->data->status) && $res->data->status !== ManualInvoice::STATUS_PENDING) {
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

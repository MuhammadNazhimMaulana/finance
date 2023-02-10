<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\BugsnagTrait;
use App\Models\Topup;
use App\Repositories\XenditRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class PendingTopup extends Command
{
    use BugsnagTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending:topup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Pending Topup';

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
        $topups = Topup::where('status', Topup::STATUS_PENDING)->take(10)->get();
        foreach ($topups as $topup) {
            DB::beginTransaction();
            try {
                $xenInvoice = XenditRepository::getInvoice($topup->xendit_id);
                if (isset($xenInvoice->data->status) && $xenInvoice->data->status !== Topup::STATUS_PENDING) {
                    $topup->status = $xenInvoice->data->status;
                    $topup->xendit_data = json_encode($xenInvoice);
                    $topup->save();
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

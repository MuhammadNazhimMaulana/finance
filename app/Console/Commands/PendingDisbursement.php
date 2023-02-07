<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\BugsnagTrait;
use App\Models\{Batch, Disbursement};
use App\Repositories\XenditRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class PendingDisbursement extends Command
{
    use BugsnagTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending:disbursement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check pending disbursement';

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
        $datas = Disbursement::withoutGlobalScopes()->orderBy('created_at', 'asc')->where('status', Disbursement::STATUS_PENDING)->cursor();
        foreach ($datas as $data) {
            DB::beginTransaction();
            try {
                $res = XenditRepository::getDisbursement($data->xendit_id);
                if (isset($res->data->status) && $res->data->status !== Disbursement::STATUS_PENDING) {
                    $data->xendit_data = json_decode(json_encode($res), true);
                    $data->status = $res->data->status;
                    $data->save();

                    if($data->category === Disbursement::BATCH)
                    {
                        $batch = Batch::find($data->batches->first()->id);

                        if($data->status === Disbursement::STATUS_COMPLETED)
                        {
                            // If Completed
                            $batch->pending_count = $batch->pending_count - 1;
                            $batch->complete_count = $batch->complete_count + 1;
                            $batch->save();
                        }else if($data->status === Disbursement::STATUS_FAILED)
                        {
                            // If Failed
                            $batch->pending_count = $batch->pending_count - 1;
                            $batch->failed_count = $batch->failed_count + 1;
                            $batch->save();
                        }

                    }
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

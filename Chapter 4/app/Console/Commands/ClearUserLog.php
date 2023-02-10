<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserLog;
use Carbon\Carbon;

class ClearUserLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:userlog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear User Log';

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
        $today = Carbon::now()->format('Y-m-d');

        UserLog::whereDate('expired_at', $today)->delete();
    }
}

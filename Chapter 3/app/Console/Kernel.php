<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Config;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ClearUserLog::class,
        Commands\BonusSchedule::class,
        Commands\SalarySchedule::class,
        Commands\PendingTopup::class,
        Commands\PendingSalary::class,
        Commands\PendingDisbursement::class,
        Commands\CheckingInvoice::class,
        Commands\CheckDonationCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Salary date
        $salary_date = Config::where('name', Config::SALARY_PAYMENT_DATE)->first();

        // Bonus date
        $bonus_date = Config::where('name', Config::BONUS_PAYMENT_DATE)->first();
        
        // Bonus type
        $bonus_type = Config::where('name', Config::BONUS_TYPE)->first();

        $schedule->command('pending:disbursement')->everyMinute()->timezone('Asia/Jakarta')->runInBackground()->withoutOverlapping();

        // If salary date is set
        if($salary_date->value != null)
        {
            $schedule->command('salary:schedule')->monthlyOn($salary_date->value, '00:00')->timezone('Asia/Jakarta')->runInBackground()->withoutOverlapping();
        }

        // If bonus date is set
        if($bonus_date->value != null)
        {
            if($bonus_type == 'tahunan')
            {
                $date = explode('-', $bonus_date->value);

                $schedule->command('bonus:schedule')->yearlyOn($date[0], $date[1], '00:00')->timezone('Asia/Jakarta')->runInBackground()->withoutOverlapping();
            }else if ($bonus_type == 'bulanan')
            {
                $schedule->command('bonus:schedule')->monthlyOn($bonus_date->value, '00:00')->timezone('Asia/Jakarta')->runInBackground()->withoutOverlapping();
            }
        }

        $schedule->command('clear:userlog')->dailyAt('04:30')->timezone('Asia/Jakarta')->withoutOverlapping();
        $schedule->command('pending:topup')->everyFiveMinutes()->timezone('Asia/Jakarta')->withoutOverlapping();
        $schedule->command('pending:salary')->everyFiveMinutes()->timezone('Asia/Jakarta')->withoutOverlapping();
        $schedule->command('checking:invoice')->everyFiveMinutes()->timezone('Asia/Jakarta')->withoutOverlapping();
        $schedule->command('checking:donation')->everyFiveMinutes()->timezone('Asia/Jakarta')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

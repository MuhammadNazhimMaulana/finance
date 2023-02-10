<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // User Profile
        $this->app->bind(
            'App\Interfaces\User\ProfileInterface',
            'App\Repositories\User\ProfileRepository'
        );
        // User Log
        $this->app->bind(
            'App\Interfaces\User\UserLogInterface',
            'App\Repositories\User\UserLogRepository'
        );
 		
 		//Company
		$this->app->bind("App\Interfaces\CompanyInterface","App\Repositories\CompanyRepository");
 		
 		//Department
		$this->app->bind("App\Interfaces\DepartmentInterface","App\Repositories\DepartmentRepository");
 		
 		//Position
		$this->app->bind("App\Interfaces\PositionInterface","App\Repositories\PositionRepository");
 		
 		//Employe
		$this->app->bind("App\Interfaces\Employe\EmployeInterface","App\Repositories\Employe\EmployeRepository");
 		
 		//Xendit
		$this->app->bind("App\Interfaces\XenditInterface","App\Repositories\XenditRepository");
 		
 		//Bank
		$this->app->bind("App\Interfaces\Employe\BankInterface","App\Repositories\Employe\BankRepository");
 		
 		//Branch
		$this->app->bind("App\Interfaces\BranchInterface","App\Repositories\BranchRepository");
 		
 		//Branch
		$this->app->bind("App\Interfaces\Api\BranchInterface","App\Repositories\Api\BranchRepository");
 		
 		//Balance
		$this->app->bind("App\Interfaces\Payment\BalanceInterface","App\Repositories\Payment\BalanceRepository");
 		
 		//Admin
		$this->app->bind("App\Interfaces\AdminInterface","App\Repositories\AdminRepository");
 		
 		//UserLog
		$this->app->bind("App\Interfaces\UserLogInterface","App\Repositories\UserLogRepository");
 		
 		//Salary
		$this->app->bind("App\Interfaces\Payment\SalaryInterface","App\Repositories\Payment\SalaryRepository");
 		
 		//Topup
		$this->app->bind("App\Interfaces\Payment\TopupInterface","App\Repositories\Payment\TopupRepository");
 		
 		//TopupReport
		$this->app->bind("App\Interfaces\Report\TopupReportInterface","App\Repositories\Report\TopupReportRepository");
 		
 		//SalaryReport
		$this->app->bind("App\Interfaces\Report\SalaryReportInterface","App\Repositories\Report\SalaryReportRepository");
 		
 		//ExportReport
		$this->app->bind("App\Interfaces\Report\ExportReportInterface","App\Repositories\Report\ExportReportRepository");
 		
 		//Disbursement
		$this->app->bind("App\Interfaces\Payment\DisbursementInterface","App\Repositories\Payment\DisbursementRepository");
 		
 		//Bonus
		$this->app->bind("App\Interfaces\Payment\BonusInterface","App\Repositories\Payment\BonusRepository");
 		
 		//DisbursementReport
		$this->app->bind("App\Interfaces\Report\DisbursementReportInterface","App\Repositories\Report\DisbursementReportRepository");

 		//Userpin
		$this->app->bind("App\Interfaces\User\UserPinInterface","App\Repositories\User\UserPinRepository");
 		
 		//Invoice
		$this->app->bind("App\Interfaces\Payment\InvoiceInterface","App\Repositories\Payment\InvoiceRepository");
 		
 		//CreateInvoice
		$this->app->bind("App\Interfaces\Invoice\CreateInvoiceInterface","App\Repositories\Invoice\CreateInvoiceRepository");
 		
 		//HistoryInvoice
		$this->app->bind("App\Interfaces\Invoice\HistoryInvoiceInterface","App\Repositories\Invoice\HistoryInvoiceRepository");
 		
 		//AccountCode
		$this->app->bind("App\Interfaces\AccountCodeInterface","App\Repositories\AccountCodeRepository");
 		
 		//InvoiceContact
		$this->app->bind("App\Interfaces\Invoice\InvoiceContactInterface","App\Repositories\Invoice\InvoiceContactRepository");
 		
 		//Contact
		$this->app->bind("App\Interfaces\ContactInterface","App\Repositories\ContactRepository");
 				
 		//InvoiceWebhook
		$this->app->bind("App\Interfaces\Webhook\InvoiceWebhookInterface","App\Repositories\Webhook\InvoiceWebhookRepository");
 		
 		//Batch
		$this->app->bind("App\Interfaces\BatchInterface","App\Repositories\BatchRepository");
 		
 		//Donation
		$this->app->bind("App\Interfaces\Invoice\DonationInterface","App\Repositories\Invoice\DonationRepository");
 		
 		//DonationWebhook
		$this->app->bind("App\Interfaces\Webhook\DonationWebhookInterface","App\Repositories\Webhook\DonationWebhookRepository");
 		
 		//FeeRule
		$this->app->bind("App\Interfaces\FeeRuleInterface","App\Repositories\FeeRuleRepository");
 		//DO_NOT_REMOVE_THIS_COMMENT
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

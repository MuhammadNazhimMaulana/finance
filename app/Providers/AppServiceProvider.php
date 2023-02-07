<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
USE App\Observers\{CompanyObserver,
    PositionObserver,
    DepartmentObserver,
    EmployeObserver,
    EmployeBankObserver,
    BranchObserver,
    AdminObserver,
    TopupObserver,
    EmployeSalaryObserver,
    DisbursementObserver,
    InvoiceContactObserver,
    AccountObserver,
    ContactObserver,
    CreateInvoiceObserver,
    UserPinObserver
};
use App\Models\{Company,
    Department,
    Position,
    Employe,
    EmployeBank,
    Branch,
    Topup,
    EmployeSalary,
    Disbursement,
    InvoiceContact,
    AccountCode,
    Contact,
    ManualInvoice,
    UserPin
};
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('layouts._partials.pagination');

        Company::observe(CompanyObserver::class);
        Department::observe(DepartmentObserver::class);
        Position::observe(PositionObserver::class);
        Employe::observe(EmployeObserver::class);
        EmployeBank::observe(EmployeBankObserver::class);
        Branch::observe(BranchObserver::class);
        User::observe(AdminObserver::class);
        Topup::observe(TopupObserver::class);
        EmployeSalary::observe(EmployeSalaryObserver::class);
        Disbursement::observe(DisbursementObserver::class);
        UserPin::observe(UserPinObserver::class);
        InvoiceContact::observe(InvoiceContactObserver::class);
        ManualInvoice::observe(CreateInvoiceObserver::class);
        AccountCode::observe(AccountObserver::class);
        Contact::observe(ContactObserver::class);
    }
}

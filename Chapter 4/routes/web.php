<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');

// Callback
Route::namespace('Webhook')->group(function () {
    Route::post('/webhook/invoice', 'InvoiceWebhookController@index');
});

// Need Auth Route
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::prefix('profile')->namespace('User')->group(function () {
        Route::get('/', 'ProfileController@index');
        Route::put('/', 'ProfileController@update')->name('update.profile');
    });
    Route::prefix('pin')->namespace('User')->group(function () {
        Route::post('/', 'PinController@store')->name('create.pin');
    });
    // Company
    Route::prefix('companies')->group(function () {
        Route::get('/', 'CompanyController@index');
        Route::post('/', 'CompanyController@store');
        Route::put('/{id}', 'CompanyController@update');
        Route::delete('/{id}', 'CompanyController@destroy');
    });
    // Webhook
    Route::namespace('Webhook')->prefix('webhooks')->group(function () {
        Route::get('/', 'DonationWebhookController@index');
        Route::post('/', 'DonationWebhookController@store');
    });
    // Department
    Route::prefix('departments')->group(function () {
        Route::get('/', 'DepartmentController@index');
        Route::post('/', 'DepartmentController@store');
        Route::put('/{id}', 'DepartmentController@update');
        Route::delete('/{id}', 'DepartmentController@destroy');
    });
    // Branch
    Route::prefix('branches')->group(function () {
        Route::get('/', 'BranchController@index');
        Route::post('/', 'BranchController@store');
        Route::put('/{id}', 'BranchController@update');
        Route::delete('/{id}', 'BranchController@destroy');
    });
    // Position
    Route::prefix('positions')->group(function () {
        Route::get('/', 'PositionController@index');
        Route::post('/', 'PositionController@store');
        Route::put('/{id}', 'PositionController@update');
        Route::delete('/{id}', 'PositionController@destroy');
    });
    // Contact
    Route::prefix('contacts')->group(function () {
        Route::post('/', 'ContactController@store');
        Route::put('/{id}', 'ContactController@update');
        Route::delete('/{id}', 'ContactController@destroy');
    });
    // Employe
    Route::namespace('Employe')->group(function () {
        Route::prefix('employes')->group(function () {
            Route::get('/', 'EmployeController@index');
            Route::post('/', 'EmployeController@store');
            Route::put('/{id}', 'EmployeController@update');
            Route::delete('/{id}', 'EmployeController@destroy');
            // Banks
            Route::get('/{id}/banks', 'BankController@index')->name('bank.index');
            Route::post('/{id}/banks', 'BankController@store')->name('bank.store');
            Route::put('/{id}/banks/{bankId}', 'BankController@update')->name('bank.update');
            Route::delete('/{id}/banks/{bankId}', 'BankController@destroy')->name('bank.delete');
            // End Bank
        });
    });
    // Transaction Batch
    Route::prefix('batches')->group(function () {
        Route::get('/getContact', 'Invoice\InvoiceContactController@contact');
        Route::get('/contact', 'ContactController@transaction'); 
        Route::get('/', 'BatchController@index');
        Route::post('/', 'BatchController@store');
        Route::get('/pdf/{disbursement_id}', 'BatchController@transferProof'); 
        Route::post('/retransfer', 'BatchController@retransfer'); 
    });
    // Transaction Batch
    Route::prefix('donation')->group(function () {
        Route::get('/', 'Invoice\DonationController@index'); 
        Route::get('/personResponsible', 'Invoice\DonationController@personResponsible');
        Route::post('/personResponsible', 'Invoice\DonationController@storePersonResponsible');
        Route::put('/personResponsible/{id}', 'Invoice\DonationController@updatePersonResponsible');
        Route::delete('/personResponsible/{id}', 'Invoice\DonationController@destroyPersonResponsible');
    });
    // JUST ROOT
    Route::middleware('root')->prefix('admins')->group(function () {
        Route::get('/', 'AdminController@index');
        Route::post('/', 'AdminController@store');
        Route::put('/{id}', 'AdminController@update');
        Route::delete('/{id}', 'AdminController@destroy');
        Route::post('/{id}/restore', 'AdminController@restore');
    });
    Route::middleware('root')->prefix('fee_rules')->group(function () {
        Route::get('/', 'FeeRuleController@index');
        Route::post('/', 'FeeRuleController@store');
        Route::delete('/{id}', 'FeeRuleController@destroy');
    });
    Route::middleware('root')->prefix('accounts')->group(function () {
        Route::get('/', 'AccountCodeController@index');
        Route::post('/', 'AccountCodeController@store');
        Route::put('/{id}', 'AccountCodeController@update');
        Route::delete('/{id}', 'AccountCodeController@destroy');
    });
    Route::middleware('root')->prefix('logs')->group(function () {
        Route::get('/', 'UserLogController@index');
    });
    // JUST ADMIN
    // Payment
    Route::namespace('Payment')->prefix('payments')->group(function () {
        Route::get('/balance/{type}', 'BalanceController@show'); // API
        Route::post('/topup', 'TopupController@store'); // TOPUP
        Route::post('/disbursement', 'DisbursementController@store'); // Disbursement
        // Salary
        Route::middleware('admin')->prefix('salary')->group(function () {
            Route::get('/', 'SalaryController@index');
            Route::post('/', 'SalaryController@store');
            Route::post('/date', 'SalaryController@storeDate');
        });
        // Bonus
        Route::get('/bonus', 'BonusController@index');
        Route::post('/bonus/data', 'BonusController@storeBonusConfig');
    });
    Route::prefix('{account_code}')->group(function () {
        Route::get('/', 'ContactController@index');
        Route::get('/contact', 'ContactController@contact');
    });
    // Report
    Route::namespace('Report')->prefix('reports')->group(function () {
        Route::get('/topup', 'TopupController@index'); // Topup Report
        Route::get('/salary', 'SalaryController@index'); // Salry Report
        Route::get('/disbursement/{category}', 'DisbursementController@index'); // Disbursement Report
        Route::get('/export', 'ExportController@index'); // Export Report
        Route::post('/export', 'ExportController@store'); // Create Export Report
    });

    Route::middleware('admin')->namespace('Invoice')->prefix('invoices')->group(function () {
        // Contact
        Route::prefix('invoiceContact')->group(function () {
            Route::get('/', 'InvoiceContactController@index'); 
            Route::post('/', 'InvoiceContactController@store');
            Route::put('/{id}', 'InvoiceContactController@update');
            Route::delete('/{id}', 'InvoiceContactController@destroy');
        });

        // Create Invoice
        Route::prefix('createInvoice')->group(function () {
            Route::get('/', 'CreateInvoiceController@index');
            Route::post('/', 'CreateInvoiceController@store');
        });
    });
    Route::namespace('Invoice')->prefix('reports')->group(function () {
        // History Invoice
        Route::prefix('historyInvoice')->group(function () {
            Route::get('/', 'HistoryInvoiceController@index');
        });
    });
});

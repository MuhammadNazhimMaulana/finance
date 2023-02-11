<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/branches', 'Api\BranchController@index');

// Donation
Route::prefix('donation')->group(function () {
    Route::get('/banks', 'Invoice\DonationController@donationBanks'); 
    Route::post('/', 'Invoice\DonationController@store');

    // Webhook
    Route::namespace('Webhook')->group(function () {
        Route::post('/webhook/donation', 'InvoiceWebhookController@index');
    });
});

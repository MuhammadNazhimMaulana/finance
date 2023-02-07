<?php

namespace App\Repositories\Webhook;;

use App\Interfaces\Webhook\InvoiceWebhookInterface;
use App\Traits\BugsnagTrait;
use App\Models\{ManualInvoice, Topup, Donation, FeeRule};
use App\Repositories\Webhook\DonationWebhookRepository;
use App\Repositories\XenditRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class InvoiceWebhookRepository implements InvoiceWebhookInterface
{
    use BugsnagTrait;

    public function index()
    {
        DB::beginTransaction();
        try{
            $payloads = request()->all();


            // Checking Callback Token
            if(isset(request()->header()['x-callback-token'][0]) && request()->header()['x-callback-token'][0] == env('XENDIT_CALLBACK_TOKEN'))
            {
                $ex = explode('-', $payloads['external_id']);

                if($ex[0] != 'topup')
                {
                    if($ex[0] != 'donation')
                    {
                        // Check Invoice
                        $invoice = ManualInvoice::where('external_id', $payloads['external_id'])->first();
                        if (isset($payloads['status']) && $payloads['status'] !== ManualInvoice::STATUS_PENDING) {
                            $invoice->xendit_data = json_encode($payloads, true);
                            $invoice->status = $payloads['status'];
                            $invoice->save();

                        }
                    }else{
                        // If it is donation
                        $invoice = Donation::where('external_id', $payloads['external_id'])->first();
                        if (isset($payloads['status']) && $payloads['status'] !== ManualInvoice::STATUS_PENDING) {
                            $invoice->xendit_data = json_encode($payloads, true);
                            $invoice->status = $payloads['status'];
                            $invoice->save();

                            // Preparing Params
                            $params = [
                                'json' => [
                                    'external_id' => $payloads['external_id'],
                                    'xendit_id' => $payloads['id'],
                                    'payment_status' => $payloads['status']
                                ]
                            ];

                            // Send The Webhook
                            $webhook = DonationWebhookRepository::sendWebhook($params);
                        }    
                    }

                }else{
                    // Check Topup
                    $topup = Topup::where('external_id', $payloads['external_id'])->first();
                    if (isset($payloads['status']) && $payloads['status'] !== Topup::STATUS_PENDING) {
                        $topup->xendit_data = json_encode($payloads, true);
                        $topup->status = $payloads['status'];
                        $topup->save();
                    }

                }     
                
            }
            DB::commit();

        }catch (Throwable $e) {
            DB::rollBack();
            $this->report($e);
        }

    }
}

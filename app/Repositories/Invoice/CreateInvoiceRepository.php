<?php

namespace App\Repositories\Invoice;;

use App\Interfaces\Invoice\CreateInvoiceInterface;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Repositories\XenditRepository;
use App\User;
use App\Models\{ManualInvoice, InvoiceContact, Company, InvoiceItem, FeeRule};
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Throwable;
use Carbon\Carbon;

class CreateInvoiceRepository implements CreateInvoiceInterface
{
    use BugsnagTrait;
    const ADMIN_CODE= 'ADM';
    const PER_PAGE = 20;

    public function index()
    {
        try {
            // Getting data of logged in user
            $user = User::find(auth()->user()->id);

            // Payment Channels
            $payment_channels = XenditRepository::paymentChannels();

            // Getting all Company
            $company = Company::paginate(self::PER_PAGE);

            // Getting all Contact for Invoice
            $contact = InvoiceContact::where('contact_code', self::ADMIN_CODE. '-'. $user->id)->paginate(self::PER_PAGE);

            return view('createInvoice.index', [
                'companies' => $company,
                'payment_channels' => $payment_channels,
                'contacts' => $contact,
                'data' => ''
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(CreateInvoiceRequest $request)
    {
        DB::beginTransaction();
        try {
            // Today's date
            $now = Carbon::now();

            // Setting up start date
            $startTime = Carbon::parse($now);

            // Setting up Finish date
            $finishTime = Carbon::parse($request->expired_at);

            // Empty array for Price
            $grand_total_price = array();

            // Check if Item is not empty
            if($request->Items == null) return back()->with('errorMsg', 'Item Belum Ada');

            // Adding each total price into grand_total_price
            foreach(json_decode($request->Items) as $itemData)
            {
                if($itemData->quantity == null)
                {
                    $itemData->quantity = 1;
                    $total_price = $itemData->price * 1;
                }else{
                    $total_price = $itemData->price * $itemData->quantity;
                }

                array_push($grand_total_price, (int) $total_price);
            }

            // All total
            $mPrice = array_sum($grand_total_price);

            // Parameter untuk membuat Invoice
            $params = [
                'json' => [
                    'account_id' => env('XENDIT_ACCOUNT_ID'),
                    'external_id' => auth()->user()->id.'-'.$request->payment_method.'-'.time(),
                    'invoice_duration' => $finishTime->diffInSeconds($startTime),
                    'amount' => (int) $mPrice,
                    'payment_methods' => [$request->payment_method],
                    'items' => json_decode($request->Items)
                ]
            ];

            // Checking Existance of Payment Method
            if($params['json']['payment_methods'][0])
            {
                $feeRule = FeeRule::where('payment_channel', $request->payment_method)->first();
                if($feeRule){
                    // Adding Fee rule
                    $params['json']["with-fee-rule"] = $feeRule->xendit_fee_rule_id;

                    // Checking Fee Percent
                    if($feeRule->xendit_percentage_fee != 0)
                    {
                        $percent_fee = $mPrice * $feeRule->xendit_percentage_fee/100;
                    }else{
                        $percent_fee = 0;
                    }

                    // Chacking Fee Flat
                    if($feeRule->xendit_flat_fee != 0)
                    {
                        $flat_fee = $feeRule->xendit_flat_fee;
                    }else{
                        $flat_fee = 0;
                    }

                    // Checking Fee Rule
                    if($feeRule->xendit_unit == strtoupper(FeeRule::PERCENT))
                    {
                        $fee_rule = $mPrice * $feeRule->margin/100;
                    }else{
                        $fee_rule = $feeRule->margin;
                    }

                    // Totaling Fee
                    $payment_fee = ceil($percent_fee) + $flat_fee + $fee_rule;
                    
                    // Tax Fee
                    $tax_fee = ceil(($percent_fee + $flat_fee) * ($feeRule->pajak/100));

                    // Adding Fee
                    if($percent_fee == 0){
                        $params['json']["fees"] = [array('type' => 'Payment Fee', 'value' => $payment_fee + $tax_fee)];
                    }
                    
                    if($percent_fee == 0){
                        // Amount plus Fee
                        $params['json']["amount"] = $mPrice + $payment_fee + $tax_fee;
                    }else{
                        $params['json']["amount"] = $mPrice;
                    }
                    
                    
                    // All Fee Total
                    $fee = $payment_fee + $tax_fee;
                }else{
                    $fee = 0;
                }

            }

            // Creating Invoice
            $invoice = XenditRepository::createInvoice($params);
            if (!isset($invoice->data->id)) return response()->error(400, null, 'Cannot create invoice');

            // Membuat Manual Invoice
            $res = new ManualInvoice();
            $res->company_id = $request->company_id;
            $res->invoice_contact_id = $request->invoice_contact_id;

            // Getting Company Data
            $company = Company::find($request->company_id);

            // Inserting data about company
            $res->company_name = $company->name;
            $res->company_email = $company->email;
            $res->company_address = $company->address;
            $res->company_phone = $company->phone;

            // Getting Kontak Data
            $contact = InvoiceContact::find($request->invoice_contact_id);
            $res->contact_name = $contact->name;
            $res->contact_email = $contact->email;
            $res->contact_address = $contact->address;
            $res->contact_phone = $contact->phone;

            // Getting Data about invoice
            $res->external_id = $invoice->data->external_id;
            $res->xendit_id = $invoice->data->id;
            $res->amount =  $percent_fee ? $mPrice - $fee : $mPrice;
            $res->fee = $fee;
            $res->grand_total_amount = $percent_fee ? $res->amount + $fee : $mPrice + $fee;
            $res->xendit_data = json_encode($invoice);
            $res->payment_url = $invoice->data->invoice_url;
            $res->expired_at = $request->expired_at;
            $res->save();

            // Create Item List
            foreach(json_decode($request->Items) as $itemData)
            {

                $InvoiceItem = new InvoiceItem;
                $InvoiceItem->manual_invoice_id = $res->id;
                $InvoiceItem->name = $itemData->name;
                $InvoiceItem->price = $itemData->price;
                $InvoiceItem->amount = $itemData->quantity;

                // If the Quantity null just times 1
                if($itemData->quantity == null)
                {
                    $InvoiceItem->total_price = $itemData->price * 1;
                }else{
                    $InvoiceItem->total_price = $itemData->price * $itemData->quantity;
                }

                $InvoiceItem->save();
            }


            DB::commit();
            return back()->with('invoiceurl', $invoice->data->invoice_url);
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

}

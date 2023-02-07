<?php

namespace App\Repositories\Invoice;;

use App\Interfaces\Invoice\DonationInterface;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Repositories\XenditRepository;
use App\Traits\{BugsnagTrait, ResponseBuilder};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;
use App\Models\{Donation, PersonResponsible, ManualInvoice, FeeRule};
use Throwable;

class DonationRepository implements DonationInterface
{
    use BugsnagTrait, ResponseBuilder;

    const PER_PAGE = 20;

    public function donationBanks()
    {
        try {
            $res = XenditRepository::banks();

            return $this->success($res->data);
        } catch (Throwable $e) {
            $this->report($e);
            return $this->error(400, null, 'SYSTEM::Failed get list of bank');
        }
    }

    public function index()
    {
        try {
            $request = request();

            $res = Donation::query();

            $data = $res->orderBy('created_at', 'desc')->paginate(self::PER_PAGE);
            
            // Total Donation
            $donations = Donation::where('status', ManualInvoice::STATUS_COMPLETED)->get();

            $total_donation = [];
            foreach($donations as $donation)
            {
                array_push($total_donation, $donation->amount);
            }

            // Person Responsible
            $person = PersonResponsible::query()->paginate(self::PER_PAGE);

            return view('donation.index', [
                'data' => $data,
                'people' => $person,
                'total_donation' => $total_donation ? array_sum($total_donation) : 0
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function personResponsible()
    {
        try {
            $request = request();

            $personResponsible = PersonResponsible::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $personResponsible->where('name', 'ilike', "%{$name}%");
            }

            $personResponsible = PersonResponsible::paginate(self::PER_PAGE);

            return view('donation.person_responsible', [
                'personResponsibles' => $personResponsible,
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function storePersonResponsible($request)
    {
        DB::beginTransaction();
        try {

            $res = new PersonResponsible();
            $res->name = $request->name;
            $res->email = $request->email;
            $res->phone = $request->phone;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Penanggung Jawab Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function updatePersonResponsible($request, $id)
    {
        DB::beginTransaction();
        try {
            $res = PersonResponsible::findOrFail($id);
            $res->name = $request->name;
            $res->email = $request->email;
            $res->phone = $request->phone;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Penanggung Jawab Updated'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function destroyPersonResponsible($id)
    {
        DB::beginTransaction();
        try {
            $res = PersonResponsible::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Penanggung Jawab Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function store(CreateInvoiceRequest $request)
    {
        DB::beginTransaction();
        try {
            
            // Checking existance of nominal
            if($request->nominal == null && $request->nominal_lain == null) return $this->error(422, null, 'Nominal Harus Diisi');

            if($request->nominal != null && is_numeric($request->nominal))
            {
                $amount = (int) $request->nominal;
            }else{
                // Amount
                $amount = (int) str_replace('.', '', $request->nominal_lain);
            }

            // Parameter untuk membuat Invoice
            $params = [
                'json' => [
                    'account_id' => env('XENDIT_ACCOUNT_ID'),
                    'external_id' => 'donation-'.$request->payment_method.'-'.$request->nama.'-'.time(),
                    'description' => $request->deskripsi_donasi,
                    'amount' => $amount,
                    'payment_methods' => [$request->payment_method],
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
                        $percent_fee = $amount * $feeRule->xendit_percentage_fee/100;
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
                        $fee_rule = $amount * $feeRule->margin/100;
                    }else{
                        $fee_rule = $feeRule->margin;
                    }

                    // Totaling Fee
                    $payment_fee = ceil($percent_fee) + $flat_fee + $fee_rule;
                    
                    // Tax Fee
                    $tax_fee = ceil(($percent_fee + $flat_fee) * ($feeRule->pajak/100));
                    
                    // Adding Fee If Percent Fee Exist
                    if($percent_fee == 0){
                        $params['json']["fees"] = [array('type' => 'Payment Fee', 'value' => $payment_fee + $tax_fee)];
                    }

                    if($percent_fee == 0){
                        // Amount plus Fee
                        $params['json']["amount"] = $amount + $payment_fee + $tax_fee;
                    }else{
                        $params['json']["amount"] = $amount;
                    }

                    // All Fee Total
                    $fee = $payment_fee + $tax_fee;
                }else{
                    $fee = 0;
                }

            }

            // Creating Invoice
            $invoice = XenditRepository::createInvoice($params);
            if (!isset($invoice->data->id)) return $this->error(400, null, 'Cannot create invoice');

            // Membuat Data Invoice
            $res = new Donation();
            $res->deskripsi_donasi = $request->deskripsi_donasi;

            // Inserting data about company
            $res->donor_name = $request->nama;
            $res->donor_email = $request->email;
            $res->donor_phone = $request->phone;
            $res->donor_bank = $request->payment_method;
            $res->donor_bank_number = $request->bank_account_number;

            // Getting Person Responsible
            $person = personResponsible::all()->first();
            $res->person_responsible_name = $person->name;
            $res->person_responsible_email = $person->email;
            $res->person_responsible_phone = $person->phone;

            // Getting Data about invoice
            $res->external_id = $invoice->data->external_id;
            $res->xendit_id = $invoice->data->id;
            $res->amount = $percent_fee ? $amount - $fee : $amount;
            $res->fee = $fee;
            $res->grand_total_amount = $percent_fee ? $res->amount + $fee : $amount + $fee;
            $res->xendit_data = json_encode($invoice);
            $res->payment_url = $invoice->data->invoice_url;
            $res->save();

            DB::commit();
            return $this->success($res);
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            return $this->error(400, null, $e->getMessage());
        }
    }

}

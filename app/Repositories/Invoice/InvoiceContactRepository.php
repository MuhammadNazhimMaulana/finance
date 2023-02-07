<?php

namespace App\Repositories\Invoice;;

use App\Interfaces\Invoice\InvoiceContactInterface;
use App\Http\Requests\Invoice\RequestInvoiceContact;
use App\Models\{Contact, InvoiceContact};
use App\User;
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Throwable;

class InvoiceContactRepository implements InvoiceContactInterface
{
    use BugsnagTrait;
    const ADMIN_CODE= 'ADM';
    const DEFAULT_CODE = 'batch';
    const PER_PAGE = 20;

    public function contact()
    {
        try {
            $request = request();

            $res = Contact::query();
            $res->where('account_code', self::DEFAULT_CODE);
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'like', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            return response($data);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function index()
    {
        try {
            // Getting data of logged in user
            $user = User::find(auth()->user()->id);

            $request = request();

            $res = InvoiceContact::where('contact_code', self::ADMIN_CODE .'-'. $user->id);
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'ilike', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            return view('invoiceContact.index', [
                'data' => $data
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(RequestInvoiceContact $request)
    {
        DB::beginTransaction();
        try {

            // Find user data
            $user = User::find(auth()->user()->id);

            $res = new InvoiceContact;
            $res->name = $request->name;
            $res->email = $request->email;
            $res->address = $request->address;
            $res->phone = $request->phone;
            $res->contact_code = self::ADMIN_CODE .'-'. $user->id;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Contact Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, RequestInvoiceContact $request)
    {
        DB::beginTransaction();
        try {
            $res = InvoiceContact::findOrFail($id);
            $res->name = $request->name;
            $res->email = $request->email;
            $res->address = $request->address;
            $res->phone = $request->phone;
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Contact Updated'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $res = InvoiceContact::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Contact Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}

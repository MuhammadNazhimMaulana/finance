<?php

namespace App\Repositories;;

use App\Interfaces\ContactInterface;
use App\Http\Requests\RequestContact;
use App\Traits\BugsnagTrait;
use Illuminate\Support\Facades\DB;
use App\Models\{Contact, AccountCode};
use Throwable;

class ContactRepository implements ContactInterface
{
    use BugsnagTrait;
    
    const DEFAULT_CODE = 'batch';
    const PER_PAGE = 20;

    public function index(string $account_code)
    {
        try {
            $request = request();
            
            // Finding the code of account
            $account = AccountCode::where('slug', $account_code)->first();

            $query = Contact::query();
            $query->where('account_code', $account->slug);
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $query->where('name', 'ilike', "%$name%");
            }

            $data = $query->paginate(self::PER_PAGE);

            $banks = XenditRepository::banks();

            // return response($account);
            return view('payment.index', [
                'data' => $data,
                'banks' => $banks,
                'slug' => $account_code
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function transaction()
    {
        try {
            $request = request();
            
            $query = Contact::query();
            $query->where('account_code', self::DEFAULT_CODE);
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $query->where('name', 'ilike', "%$name%");
            }

            $data = $query->paginate(self::PER_PAGE);

            $banks = XenditRepository::banks();

            // return response($account);
            return view('payment.contact.index', [
                'data' => $data,
                'banks' => $banks,
                'slug' => self::DEFAULT_CODE
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function contact(string $account_code)
    {
        try {
            $request = request();
            
            // Finding the code of account
            $account = AccountCode::where('slug', $account_code)->first();

            $query = Contact::query();
            $query->where('account_code', $account->slug);
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $query->where('name', 'ilike', "%$name%");
            }

            $data = $query->paginate(self::PER_PAGE);

            $banks = XenditRepository::banks();

            // return response($account);
            return view('payment.contact.index', [
                'data' => $data,
                'banks' => $banks,
                'slug' => $account_code
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(RequestContact $request)
    {
        DB::beginTransaction();
        try {
            $insert = new Contact;
            $insert->account_code = $request->account_code;
            $insert->name = $request->name;
            $insert->email = $request->email;
            $insert->bank_code = $request->bank_code;
            $insert->bank_name = $request->bank_name;
            $insert->bank_account_holder_name = $request->bank_account_holder_name;
            $insert->bank_account_number = $request->bank_account_number;
            $insert->save();

            DB::commit();

            return back()->with('status', 'Kontak berhasil disimpan');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', 'Terjadi kesalahan');
        }
    }

    public function update(int $id, RequestContact $request)
    {
        DB::beginTransaction();
        try {
            $insert = Contact::find($id);
            if (!$insert) return back()->with('errorMsg', 'Kontak tidak ditemukan');
            $insert->account_code = $request->account_code;
            $insert->name = $request->name;
            $insert->email = $request->email;
            $insert->bank_code = $request->bank_code;
            $insert->bank_name = $request->bank_name;
            $insert->bank_account_holder_name = $request->bank_account_holder_name;
            $insert->bank_account_number = $request->bank_account_number;
            $insert->save();

            DB::commit();

            return back()->with('status', 'Kontak berhasil diperbaharui');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', 'Terjadi kesalahan');
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $insert = Contact::find($id);
            if (!$insert) return back()->with('errorMsg', 'Kontak tidak ditemukan');
            $insert->delete();

            DB::commit();

            return back()->with('status', 'Kontak berhasil dihapus');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', 'Terjadi kesalahan');
        }
    }
}

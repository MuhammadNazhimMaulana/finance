<?php

namespace App\Repositories;;

use App\Interfaces\AccountCodeInterface;
use App\Http\Requests\AccountRequest;
use App\Models\AccountCode;
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Throwable;

class AccountCodeRepository implements AccountCodeInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            $request = request();

            $res = AccountCode::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'ilike', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            return view('account.index', [
                'data' => $data
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(AccountRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = new AccountCode();
            $res->name = $request->name;
            $res->slug = strtolower($request->name);
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Account Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, AccountRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = AccountCode::findOrFail($id);
            $res->name = $request->name;
            $res->slug = strtolower($request->name);
            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Account Updated'));
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
            $res = AccountCode::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Account Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}

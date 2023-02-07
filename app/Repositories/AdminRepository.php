<?php

namespace App\Repositories;;

use App\Interfaces\AdminInterface;
use App\Http\Requests\Admin\{StoreRequest, UpdateRequest};
use App\Traits\BugsnagTrait;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AdminRepository implements AdminInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            $request = request();

            $res = User::query();
            $res->withTrashed();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('name', 'ilike', "%{$name}%");
            }

            $res->where('id', '!=', auth()->user()->id);

            $data = $res->orderBy('created_at', 'desc')->paginate(self::PER_PAGE);

            return view('admin.index', [
                'data' => $data
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt(time());
            $user->save();
            $user->assignRole('admin');

            $token = app('auth.password.broker')->createToken($user);

            $user->sendPasswordResetNotification($token);

            DB::commit();

            return back()->with('successMsg', __('Admin Created'));
        } catch (Throwable $e) {
            DB::rollBack();
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, UpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            if ($request->filled('reset')) {
                $token = app('auth.password.broker')->createToken($user);

                $user->sendPasswordResetNotification($token);
            }

            DB::commit();

            if ($request->filled('reset')) {
                if (Schema::hasTable('sessions')) {
                    DB::table('sessions')->where('user_id', $user->id)->delete();
                }
            }

            return back()->with('successMsg', __('Admin Updated'));
        } catch (Throwable $e) {
            DB::rollBack();
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $res = User::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Admin Deleted'));
        } catch (Throwable $e) {
            DB::rollBack();
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function restore(int $id)
    {
        DB::beginTransaction();
        try {
            $res = User::withTrashed()->findOrFail($id);
            $res->restore();

            DB::commit();

            return back()->with('successMsg', __('Admin Restored'));
        } catch (Throwable $e) {
            DB::rollBack();
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }
}

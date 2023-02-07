<?php

namespace App\Repositories\User;;

use App\Interfaces\User\UserPinInterface;
use App\Http\Requests\User\Pin\StoreRequest;
use App\Models\UserPin;
use App\Traits\BugsnagTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserPinRepository implements UserPinInterface
{
    use BugsnagTrait;

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            if ($user->pin) return back()->with('errorMsg', __('Anda sudah mengkonfigurasi PIN sebeleumnya'));

            $insert = new UserPin;
            $insert->user_id = $user->id;
            $insert->value = Hash::make($request->pin);
            $insert->save();

            DB::commit();

            return back()->with('successMsg', __('Pin berhasil dibuat'));
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', __('Terjadi kesalahan'));
        }
    }
}

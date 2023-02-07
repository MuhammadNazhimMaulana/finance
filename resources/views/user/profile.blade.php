@extends('layouts.app')
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Akun') }}</span></li>
                    {{--
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>Breadcrumbs</span></li>
                     --}}
                </ol>
            </nav>
        </div>
    </li>
</ul>
@endsection
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-md-4 layout-spacing">
            <div class="widget widget-content-area br-4">
                @include('layouts._partials.alert')
                @if (Auth::user()->hasRole('admin') && !Auth::user()->pin)
                    <h4>Buat PIN Baru</h4>
                    <p>Pin harus berupa 6 angka</p>
                    <br>
                    <form method="POST" action="{{ route('create.pin') }}">
                        @csrf
                        <div class="form-row mb-3">
                            <div class="form-group col-md-6">
                                <label>{{ __('PIN') }}</label>
                                <input type="password" name="pin" class="form-control form-control-sm" maxlength="6" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Konfirmasi PIN') }}</label>
                                <input type="password" name="pin_confirmation" class="form-control form-control-sm" maxlength="6" required>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-outline-success btn-block">{{ __('BUAT PIN') }}</button>
                    </form>
                    <br><br><br>
                @endif
                <form method="POST" action="{{ route('update.profile') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row mb-3">
                        <div class="form-group col-md-6">
                            <label>{{ __('Nama Lengkap') }}</label>
                            <input type="text" class="form-control form-control-sm" value="{{ old('name') ? old('name') : Auth::user()->name }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Alamat E-Mail') }}</label>
                            <input type="email" class="form-control form-control-sm" value="{{ old('email') ? old('email') : Auth::user()->email }}" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Kata Sandi') }}</label>
                            <input name="password" type="password" class="form-control form-control-sm" placeholder="*************">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Ulangi Kata Sandi') }}</label>
                            <input name="password_confirmation" type="password" class="form-control form-control-sm" placeholder="*************">
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-outline-success btn-block">{{ __('Simpan') }}</button>
                </form>
            </div>
        </div>
        <div class="col-md-8 layout-spacing">
            <div class="widget widget-content-area br-4">
                <h6 class="pb-2">{{ __('5 log aktivitas terbaru Anda') }}</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('Waktu') }}</th>
                                <th scope="col">{{ __('Aktifitas') }}</th>
                                <th scope="col">{{ __('Alamat IP') }}</th>
                                <th scope="col">{{ __('User Agent') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d F Y H:i:s') }}</td>
                                    <td><span class="badge badge-secondary badge-pill">{{ $log->action }}</span></td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>{{ $log->user_agent }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

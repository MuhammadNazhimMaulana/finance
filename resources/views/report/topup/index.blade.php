@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="/themes/cork/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/flatpickr/flatpickr.js"></script>
    <script type="text/javascript">
        flatpickr(document.getElementsByClassName('isdate'), {
            maxDate: new Date()
        });
    </script>
@endpush
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Laporan Topup') }}</span></li>
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
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="mb-4">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-sm-12 col-lg-4">
                                    <input name="amount" type="number" class="form-control form-control-sm" placeholder="Nominal..." @if(Request::filled('amount')) value="{{ Request::input('amount') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 col-lg-4">
                                    <input name="date" type="text" class="form-control form-control-sm isdate" placeholder="Tanggal..." @if(Request::filled('date')) value="{{ Request::input('date') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 col-lg-4 pt-1">
                                    <button class="btn btn-primary btn-block" type="submit">{{ __('Filter') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if (!Request::filled('amount'))
                <div class="card component-card_1 text-center">
                    <div class="card-body">
                        <h1>
                            <svg class="svg-inline--fa fa-wallet fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="wallet" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor" d="M461.2 128H80c-8.84 0-16-7.16-16-16s7.16-16 16-16h384c8.84 0 16-7.16 16-16 0-26.51-21.49-48-48-48H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h397.2c28.02 0 50.8-21.53 50.8-48V176c0-26.47-22.78-48-50.8-48zM416 336c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"></path>
                            </svg>
                        </h1>
                        <h5 class="card-title">TOTAL SUKSES TOPUP (@if(Request::filled('date')) {{ \Carbon\Carbon::parse(Request::input('date'))->format('d F Y') }} @else Keseluruhan @endif)</h5>
                        <h3 class="text-success">Rp{{ \App\Helpers\CurrencyHelper::toIDR($successTopupAmount) }}</h3>
                    </div>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-dark">
                    <thead class="thead-dark|thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nominal</th>
                            <th scope="col">Fee</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Oleh</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($item->total) }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($item->fee) }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($item->amount) }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y H:i:s') }}</td>
                                <td>{!! $item->user_data['name'] !!}</td>
                                <td>
                                    @if ($item->status === \App\Models\Topup::STATUS_PENDING)
                                        <a class="btn btn-success btn-sm" href="{{ $item->payment_url }}" target="_Blank">{{ __('BAYAR') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{
                $data->appends([
                    'amount' => Request::filled('amount') ? Request::input('amount') : null,
                    'date' => Request::filled('date') ? Request::input('date') : null
                ])->links()
            }}
        </div>
    </div>
</div>
@endsection

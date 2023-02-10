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
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ $title }}</span></li>
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
                    <div class="col-12">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-sm-12 col-lg-2">
                                    <input name="to_name" type="text" class="form-control form-control-sm" placeholder="Nama..." @if(Request::filled('to_name')) value="{{ Request::input('to_name') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <input name="amount" type="number" class="form-control form-control-sm" placeholder="Nominal..." @if(Request::filled('amount')) value="{{ Request::input('amount') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <input name="date" type="text" class="form-control form-control-sm isdate" placeholder="Tanggal..." @if(Request::filled('date')) value="{{ Request::input('date') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <select class="custom-select" name="status">
                                        <option value="">--Status--</option>
                                        <option value="{{  \App\Models\Disbursement::STATUS_FAILED }}" @if(Request::filled('status') && Request::input('status') == \App\Models\Disbursement::STATUS_FAILED) selected @endif>{{  \App\Models\Disbursement::STATUS_FAILED }}</option>
                                        <option value="{{  \App\Models\Disbursement::STATUS_PENDING }}" @if(Request::filled('status') && Request::input('status') == \App\Models\Disbursement::STATUS_PENDING) selected @endif>{{  \App\Models\Disbursement::STATUS_PENDING }}</option>
                                        <option value="{{  \App\Models\Disbursement::STATUS_COMPLETED }}" @if(Request::filled('status') && Request::input('status') == \App\Models\Disbursement::STATUS_COMPLETED) selected @endif>{{  \App\Models\Disbursement::STATUS_COMPLETED }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-4 pt-1">
                                    <button class="btn btn-primary btn-block" type="submit">{{ __('Filter') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-dark">
                    <thead class="thead-dark|thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Nominal</th>
                            <th scope="col">Fee</th>
                            <th scope="col">Total</th>
                            <th scope="col">Bank Tujuan</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Oleh</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->to_name }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($item->total) }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($item->fee) }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($item->amount) }}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>{{ $item->bank_name }}</li>
                                        <li>{{ $item->bank_account_number }}</li>
                                        <li>{{ $item->bank_account_holder_name }}</li>
                                    </ul>
                                </td>
                                <td>{{ $item->description }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y H:i:s') }}</td>
                                <td>{!! $item->transferred_by['name'] !!}</td>
                                <td>
                                    @if ($item->status === \App\Models\Disbursement::STATUS_COMPLETED)
                                        <span class="badge badge-pill badge-success">{{ $item->status }}</span>
                                    @elseif($item->status === \App\Models\Disbursement::STATUS_PENDING)
                                        <span class="badge badge-pill badge-warning">{{ $item->status }}</span>
                                    @elseif($item->status === \App\Models\Disbursement::STATUS_FAILED)
                                        <span class="badge badge-pill badge-danger">{{ $item->status }} {{ isset($item->xendit_data['data']['failure_code']) ? ' - '.$item->xendit_data['data']['failure_code'] : null }}</span>
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
                    'date' => Request::filled('date') ? Request::input('date') : null,
                    'status' => Request::filled('status') ? Request::input('status') : null,
                    'to_name' => Request::filled('to_name') ? Request::input('to_name') : null,
                ])->links()
            }}
        </div>
    </div>
</div>
@endsection

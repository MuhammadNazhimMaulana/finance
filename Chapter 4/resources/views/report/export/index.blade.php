@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="/themes/cork/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/flatpickr/flatpickr.js"></script>
    <script type="text/javascript">
        /*
        flatpickr(document.getElementsByClassName('isdate'), {
            maxDate: new Date()
        });
        */
        flatpickr('#isdate', {
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            maxDate: new Date(),
            mode: "range",
            onChange: [function(selectedDates){
                const dateArr = selectedDates.map(date => this.formatDate(date, "Y-m-d"));
                $("#from").val(dateArr[0]);
                $("#to").val(dateArr[1]);
            }]
        });
    </script>
@endpush
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Export Laporan Keuangan') }}</span></li>
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
            <div class="my-4">
                @include('layouts._partials.alert')
            </div>
            <div class="mb-4">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <form method="POST" action="{{ action('Report\ExportController@store') }}">
                            @csrf
                            <input type="hidden" name="type" value="TRANSACTIONS">
                            <input type="hidden" name="from" id="from" value="">
                            <input type="hidden" name="to" id="to" value="">
                            <div class="form-row">
                                <div class="form-group col-sm-12 col-lg-3">
                                    <select class="custom-select" name="category" required>
                                        <option value="">--AKUN--</option>
                                        @foreach ($accounts as $account)
                                        <option value="{{ $account->name }}" @if(Request::filled('category') && Request::input('category') == $account->id) selected @endif>{{ strtoupper($account->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <select class="custom-select" name="status" required>
                                        <option value="">--STATUS--</option>
                                        <option value="{{  \App\Models\Disbursement::STATUS_COMPLETED }}" @if(Request::filled('category') && Request::input('category') == \App\Models\Disbursement::STATUS_COMPLETED) selected @endif>{{  \App\Models\Disbursement::STATUS_COMPLETED }}</option>
                                        <option value="{{  \App\Models\Disbursement::STATUS_PENDING }}" @if(Request::filled('category') && Request::input('category') == \App\Models\Disbursement::STATUS_PENDING) selected @endif>{{  \App\Models\Disbursement::STATUS_PENDING }}</option>
                                        <option value="{{  \App\Models\Disbursement::STATUS_FAILED }}" @if(Request::filled('category') && Request::input('category') == \App\Models\Disbursement::STATUS_FAILED) selected @endif>{{  \App\Models\Disbursement::STATUS_FAILED }}</option>
                                        <option value="ALL" @if(Request::filled('category') && Request::input('category') == 'ALL') selected @endif>ALL</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <input id="isdate" type="text" class="form-control form-control-sm" placeholder="Tanggal..." @if(Request::filled('from')) value="{{ Request::input('from') }}" @endif required>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3 pt-1">
                                    <button class="btn btn-primary btn-block" type="submit">{{ __('EXPORT') }}</button>
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
                            <th scope="col">Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Dari Tanggal</th>
                            <th scope="col">Sampai Tanggal</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->data->data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->type }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->from)->format('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->to)->format('d F Y') }}</td>
                                <td>
                                    @if ($item->url)
                                        <a class="btn btn-success btn-sm" href="{{ $item->url }}" target="_Blank">{{ __('UNDUH') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

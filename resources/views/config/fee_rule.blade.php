@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@endpush
@section('breadcrumb')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Fee Rule') }}</span></li>
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
            <h4 class="mb-3">Fee Rule</h4>
            <div class="mb-4 mt-4">
                @include('layouts._partials.alert')
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <form method="POST" action="{{ action('FeeRuleController@store') }}">
                        @csrf
                        {{-- Start Of Creating Fee Rule--}}
                        <div class="form-row align-items-end">

                            {{-- Name--}}
                            <div class="form-group col-sm-12 col-lg-3">
                                <label>{{ __('Name') }}</label>
                                <input name="name" type="text" class="form-control form-control-sm isdate" placeholder="Nama Fee Rule" @if(Request::filled('name')) value="{{ Request::input('name') }}"  @endif required>
                            </div>

                            {{-- Description--}}
                            <div class="form-group col-sm-12 col-lg-3">
                                <label>{{ __('Description') }}</label>
                                <input name="description" type="text" class="form-control form-control-sm isdate" placeholder="Deskripsi" @if(Request::filled('description')) value="{{ Request::input('description') }}"  @endif>
                            </div>

                            <div class="form-group col-sm-12 col-lg-3">
                                <label>{{ __('Unit') }}</label>
                                <select class="custom-select" name="unit" required>
                                    <option value="">--Pilih--</option>
                                    <option value="percent" @if(Request::filled('unit') && Request::input('unit') == 'percent') selected @endif>Percent</option>
                                    <option value="flat" @if(Request::filled('unit') && Request::input('unit') == 'flat') selected @endif>Flat</option>
                                </select>
                            </div>

                            <div class="form-group col-sm-12 col-lg-3">
                                <label for="payment_channel">{{ __('Payment Channel') }}</label>
                                <select class="custom-select" name="payment_channel" required>
                                    <option value="">--Pilih--</option>
                                    @foreach ($payment_channels->data as $d)
                                        <option value="{{ $d->channel_code }}">{{ $d->channel_code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Margin --}}
                            <div class="form-group col-sm-12 col-lg-3">
                                <label>{{ __('Margin') }}</label>
                                <input name="margin" type="number" step=".01" class="form-control form-control-sm isdate" placeholder="1 2 3 ..." @if(Request::filled('margin')) value="{{ Request::input('margin') }}"  @endif required>
                            </div>

                            <div class="form-group col-sm-12 col-lg-3">
                                <label>{{ __('Xendit Percent Fee') }}</label>
                                <input name="xendit_percentage_fee" step=".01" type="number" class="form-control form-control-sm isdate" placeholder="1 2 3 ..." @if(Request::filled('xendit_percentage_fee')) value="{{ Request::input('xendit_percentage_fee') }}"  @endif>
                            </div>

                            <div class="form-group col-sm-12 col-lg-2">
                                <label>{{ __('Xendit Flat Fee') }}</label>
                                <input name="xendit_flat_fee" type="number" class="form-control form-control-sm isdate" placeholder="100 200 ..." @if(Request::filled('xendit_flat_fee')) value="{{ Request::input('xendit_flat_fee') }}"  @endif>
                            </div>

                            <div class="form-group col-sm-12 col-lg-2">
                                <label>{{ __('Pajak') }}</label>
                                <input name="pajak" type="number" step=".01" class="form-control form-control-sm isdate" value="11" @if(Request::filled('pajak')) value="{{ Request::input('pajak') }}"  @endif>
                            </div>

                            {{-- Create Fee Rule Button --}}
                            <div class="form-group col-sm-12 col-lg-2">
                                <button type="submit" class="btn btn-success mb-1 w-100">
                                    Create Fee Rule
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-dark">
                        <thead class="thead-dark|thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Rule</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Payment Channel</th>
                                <th scope="col">Margin</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Xendit Flat Fee</th>
                                <th scope="col">Xendit Percentage Fee</th>
                                <th scope="col">Pajak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $fee_rule)
                                <tr>
                                    <th scope="col">{{ $fee_rule->id }}</th>
                                    <th scope="col">{{ $fee_rule->rule_name }}</th>
                                    <th scope="col">{{ $fee_rule->description }}</th>
                                    <th scope="col">{{ $fee_rule->payment_channel }}</th>
                                    <th scope="col">{{ $fee_rule->margin }}</th>
                                    <th scope="col">{{ $fee_rule->xendit_unit }}</th>
                                    <th scope="col">{{ $fee_rule->xendit_flat_fee }}</th>
                                    <th scope="col">{{ $fee_rule->xendit_percentage_fee }}</th>
                                    <th scope="col">{{ \App\Helpers\CurrencyHelper::toIDR($fee_rule->pajak) }}%</th>
                                    <th>
                                        <ul class="table-controls">
                                            <li>
                                                <a href="javascript:void(0);" title="DELETE" data-toggle="modal" data-target="#delete{{ $fee_rule->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>

@foreach ($data as $fee)
    {{-- Delete --}}
    <div class="modal fade" id="delete{{ $fee->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">HAPUS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('FeeRuleController@destroy', ['id' => $fee->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rule_name">Nama Rule</label>
                            <input value="{{ $fee->rule_name }}" name="rule_name" type="text" class="form-control" id="rule_name" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection

@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="/themes/cork/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/flatpickr/flatpickr.js"></script>
    <script src="/themes/cork/plugins/select2/select2.min.js"></script>
    <script type="text/javascript">
        flatpickr(document.getElementsByClassName('isdate'), {
            maxDate: new Date()
        });
        $('.branch').each(function() {
            var $p = $(this).parent();
            $(this).select2({
                placeholder: 'Cari Cabang',
                minimumInputLength: 3,
                dropdownParent: $p,
                allowClear: true,
                ajax: {
                    url: '/api/branches',
                    dataType: 'json'
                }
            });
        });
    </script>
@endpush
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Laporan Gaji') }}</span></li>
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
            <h4 class="mb-3">Data karyawan yang sudah di gaji pada bulan <span class="badge badge-pill badge-secondary">{{ Request::filled('date') ? \Carbon\Carbon::parse(Request::input('date'))->format('F Y') : \Carbon\Carbon::now()->format('F Y') }}</span></h4>
            <div class="mb-4 mt-4">
                @include('layouts._partials.alert')
                <div class="row">
                    <div class="col-12">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-sm-12 col-lg-3">
                                    <input name="name" type="text" class="form-control form-control-sm" placeholder="Nama..." @if(Request::filled('name')) value="{{ Request::input('name') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <select class="custom-select" name="company_id">
                                        <option value="">--Perusahaan--</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" @if(Request::filled('company_id') && Request::input('company_id') == $company->id) selected @endif>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <select class="custom-select branch" name="branch_id">
                                        @if ($branchId && $branchName)
                                            <option value="{{ $branchId }}" selected>{{ $branchName }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <select class="custom-select" name="department_id">
                                        <option value="">--Departemen--</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @if(Request::filled('department_id') && Request::input('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <select class="custom-select" name="position_id">
                                        <option value="">--Jabatan--</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}" @if(Request::filled('position_id') && Request::input('position_id') == $position->id) selected @endif>{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <input name="date" type="text" class="form-control form-control-sm isdate" placeholder="Tanggal..." @if(Request::filled('date')) value="{{ Request::input('date') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <select class="custom-select" name="status">
                                        <option value="">--Status--</option>
                                        <option value="{{  \App\Models\EmployeSalary::STATUS_FAILED }}" @if(Request::filled('status') && Request::input('status') == \App\Models\EmployeSalary::STATUS_FAILED) selected @endif>{{  \App\Models\EmployeSalary::STATUS_FAILED }}</option>
                                        <option value="{{  \App\Models\EmployeSalary::STATUS_PENDING }}" @if(Request::filled('status') && Request::input('status') == \App\Models\EmployeSalary::STATUS_PENDING) selected @endif>{{  \App\Models\EmployeSalary::STATUS_PENDING }}</option>
                                        <option value="{{  \App\Models\EmployeSalary::STATUS_COMPLETED }}" @if(Request::filled('status') && Request::input('status') == \App\Models\EmployeSalary::STATUS_COMPLETED) selected @endif>{{  \App\Models\EmployeSalary::STATUS_COMPLETED }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <button class="btn btn-primary btn-block" type="submit">{{ __('Filter') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card component-card_1 text-center">
                <div class="card-body">
                    <h1>
                        <svg class="svg-inline--fa fa-wallet fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="wallet" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                            <path fill="currentColor" d="M461.2 128H80c-8.84 0-16-7.16-16-16s7.16-16 16-16h384c8.84 0 16-7.16 16-16 0-26.51-21.49-48-48-48H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h397.2c28.02 0 50.8-21.53 50.8-48V176c0-26.47-22.78-48-50.8-48zM416 336c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"></path>
                        </svg>
                    </h1>
                    <h5 class="card-title">TOTAL GAJI YANG SUDAH SUKSES DIBAYAR</h5>
                    <h3 class="text-success">Rp{{ \App\Helpers\CurrencyHelper::toIDR($totalSalary) }}</h3>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-dark">
                    <thead class="thead-dark|thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Perusahaan</th>
                            <th scope="col">Cabang</th>
                            <th scope="col">Departemen</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Nominal</th>
                            <th scope="col">Fee</th>
                            <th scope="col">Total</th>
                            <th scope="col">Bank Tujuan</th>
                            <th scope="col">Ditransfer Oleh</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $employe)
                            <tr>
                                <td>{{ $employe->id }}</td>
                                <td>{{ $employe->employe_data['name'] }}</td>
                                <td>{{ $employe->employe_data['company']['name'] }}</td>
                                <td>{{ $employe->employe['branch']['name'] }}</td>
                                <td>{{ $employe->employe['department']['name'] }}</td>
                                <td>{{ $employe->employe['position']['name'] }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($employe->amount) }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($employe->fee) }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($employe->total) }}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>{{ $employe->employe_bank_data['name'] }}</li>
                                        <li>{{ $employe->employe_bank_data['account_number'] }}</li>
                                        <li>{{ $employe->employe_bank_data['account_holder_name'] }}</li>
                                    </ul>
                                </td>
                                <td>{{ $employe->transferred_by['name'] }}</td>
                                <td>
                                    @if ($employe->status === \App\Models\EmployeSalary::STATUS_COMPLETED)
                                        <span class="badge badge-pill badge-success">{{ $employe->status }}</span>
                                    @elseif($employe->status === \App\Models\EmployeSalary::STATUS_PENDING)
                                        <span class="badge badge-pill badge-warning">{{ $employe->status }}</span>
                                    @elseif($employe->status === \App\Models\EmployeSalary::STATUS_FAILED)
                                        <span class="badge badge-pill badge-danger">{{ $employe->status }} {{ isset($employe->xendit_data['data']['failure_code']) ? ' - '.$employe->xendit_data['data']['failure_code'] : null }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($employe->status === \App\Models\EmployeSalary::STATUS_FAILED)
                                        <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#transfer{{ $employe->id }}">{{ __('TRANSFER ULANG') }}</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{
                $data->appends([
                    'name' => Request::filled('name') ? Request::input('name') : null,
                    'company_id' => Request::filled('company_id') ? Request::input('company_id') : null,
                    'branch_id' => Request::filled('branch_id') ? Request::input('branch_id') : null,
                    'department_id' => Request::filled('department_id') ? Request::input('department_id') : null,
                    'position_id' => Request::filled('position_id') ? Request::input('position_id') : null,
                    'status' => Request::filled('status') ? Request::input('status') : null,
                    'date' => Request::filled('date') ? Request::input('date') : null
                ])->links()
            }}
        </div>
    </div>
</div>
@foreach ($data as $employe)
    {{-- Edit Modal --}}
    <div class="modal fade" id="transfer{{ $employe->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">TRANSFER ULANG GAJI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('Payment\SalaryController@store') }}">
                    @csrf
                    <input type="hidden" name="employe_salary_id" value="{{ $employe->id }}">
                    <input type="hidden" name="employe_id" value="{{ $employe->employe->id }}">
                    <input type="hidden" name="try_count" value="{{ (int)$employe->try_count + 1 }}">
                    <div class="modal-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td>{{ $employe->employe->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bulan</td>
                                        <td>:</td>
                                        <td>{{ \Carbon\Carbon::now()->format('F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nominal</td>
                                        <td>:</td>
                                        <td>{{ \App\Helpers\CurrencyHelper::toIDR($employe->amount) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="name">Bank Tujuan</label>
                            <select class="custom-select" name="employe_bank_id">
                                @foreach ($employe->employe->employebanks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }} - {{ $bank->account_holder_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">PIN</label>
                            <input name="pin" type="password" class="form-control" maxlength="6" minlength="6" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success showownloading" data-mid="transfer{{ $employe->id }}">KONFIRMASI</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

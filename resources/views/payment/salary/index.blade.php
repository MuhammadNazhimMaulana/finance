@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link rel="stylesheet" type="text/css" href="{{ '/css/hide-date.css' }}">
    <link href="/themes/cork/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/flatpickr/flatpickr.js"></script>
    <script src="/themes/cork/plugins/select2/select2.min.js"></script>
    <script type="text/javascript">
        flatpickr(document.getElementsByClassName('isdate'), {
            defaultDate:  @json($config),
            dateFormat: "d",
            allowInput: true,
            // minDate: "today"
        });

        // eventListener
        const change = document.querySelector("#ubah");

        // If "Ubah" is clicked
        change.addEventListener('click', e => {
        e.preventDefault()
            date = document.querySelector("#change_date")
            save = document.querySelector("#simpan")
            hide = document.querySelector("#hide")

            // Change Atribute
            date.removeAttribute("disabled")
            date.removeAttribute("readonly")

            // Change Class
            hide.classList.add("d-none")

            // Change Class
            save.classList.remove("d-none")
        })
        
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
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Pembayaran Gaji') }}</span></li>
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
            <h4 class="mb-3">Data karyawan yang belum di gaji pada bulan <span class="badge badge-pill badge-secondary">{{ \Carbon\Carbon::now()->format('F Y') }}</span></h4>
            <div class="mb-4 mt-4">
                @include('layouts._partials.alert')
                <div class="row">
                    <div class="col-12">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-sm-12 col-lg-2">
                                    <input name="name" type="text" class="form-control form-control-sm" placeholder="Nama..." @if(Request::filled('name')) value="{{ Request::input('name') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <select class="custom-select" name="company_id">
                                        <option value="">--Perusahaan--</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" @if(Request::filled('company_id') && Request::input('company_id') == $company->id) selected @endif>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <select class="custom-select branch" name="branch_id">
                                        @if ($branchId && $branchName)
                                            <option value="{{ $branchId }}" selected>{{ $branchName }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <select class="custom-select" name="department_id">
                                        <option value="">--Departemen--</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @if(Request::filled('department_id') && Request::input('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <select class="custom-select" name="position_id">
                                        <option value="">--Jabatan--</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}" @if(Request::filled('position_id') && Request::input('position_id') == $position->id) selected @endif>{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <button class="btn btn-primary btn-block" type="submit">{{ __('Filter') }}</button>
                                </div>
                            </div>
                        </form>
                        <form method="POST" action="{{ action('Payment\SalaryController@storeDate') }}">
                            @csrf
                            <div class="form-row">
                                @if($config !== null)
                                <div class="form-group col-sm-12 col-lg-3">
                                    <input name="payment_date" type="date" class="form-control form-control-sm isdate" placeholder="Tanggal Gajian Tiap Bulan" @if(Request::filled('payment_date')) value="{{ Request::input('payment_date') }}" @endif id="change_date" disabled="true">
                                </div>
                                <div class="form-group col-sm-12 col-lg-2" id="hide">
                                    <button class="btn btn-success btn-block" id="ubah">{{ __('Ubah') }}</button>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2 d-none" id="simpan">
                                    <button class="btn btn-success btn-block" type="submit">{{ __('Save') }}</button>
                                </div>
                                @endif
                                @if($config == null)
                                <div class="form-group col-sm-12 col-lg-3">
                                    <input name="payment_date" type="date" class="form-control form-control-sm isdate" placeholder="Tanggal Gajian Tiap Bulan" @if(Request::filled('payment_date')) value="{{ Request::input('payment_date') }}" @endif required>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <button class="btn btn-success btn-block" type="submit">{{ __('Save') }}</button>
                                </div>
                                @endif
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
                    <h5 class="card-title">TOTAL GAJI YANG HARUS DIBAYAR</h5>
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
                            <th scope="col">Gaji /bulan</th>
                            {{-- <th scope="col"></th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $employe)
                            <tr>
                                <td>{{ $employe->id }}</td>
                                <td>{{ $employe->name }}</td>
                                <td>{{ $employe->company->name }}</td>
                                <td>{{ $employe->branch->name }}</td>
                                <td>{{ $employe->department->name }}</td>
                                <td>{{ $employe->position->name }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($employe->monthly_salary) }}</td>
                                {{-- <td>
                                    <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#transfer{{ $employe->id }}">{{ __('TRANSFER') }}</button>
                                </td> --}}
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
                    'position_id' => Request::filled('position_id') ? Request::input('position_id') : null
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
                    <h5 class="modal-title" id="exampleModalLongTitle">TRANSFER GAJI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('Payment\SalaryController@store') }}">
                    @csrf
                    <input type="hidden" name="employe_id" value="{{ $employe->id }}">
                    <div class="modal-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td>{{ $employe->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bulan</td>
                                        <td>:</td>
                                        <td>{{ \Carbon\Carbon::now()->format('F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nominal</td>
                                        <td>:</td>
                                        <td>{{ \App\Helpers\CurrencyHelper::toIDR($employe->monthly_salary) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="name">Bank Tujuan</label>
                            <select class="custom-select" name="employe_bank_id">
                                @foreach ($employe->employebanks as $bank)
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

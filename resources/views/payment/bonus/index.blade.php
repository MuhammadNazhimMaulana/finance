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
        var sep = '.';

        let bonus = document.querySelector("#bonus");
        const tanggal = document.querySelector("#tanggal");

        
        let formatDate = ""

        // Checking if config is not empty
        if(@json($config_date))
        {
            // Change Class
            tanggal.classList.remove("d-none")

            if(@json($config_type->value) == 'tahunan')
            {
                formatDate = "m-d"
            }else if(@json($config_type->value) == 'bulanan')
            {
                formatDate = "d"
            }

            flatpickr(document.getElementsByClassName('isdate'), {
                defaultDate:  @json($config_date),
                dateFormat: formatDate,
                allowInput: true,
                // minDate: "today"
            });
        }

        // eventListener
        const change = document.querySelector("#ubah");

        // If "Ubah" is clicked
        if(change)
        {
            change.addEventListener('click', e => {
            e.preventDefault()
                bonus = document.querySelector("#bonus")
                date = document.querySelector("#bonus_date")
                percent = document.querySelector("#bonus_percent")
                save = document.querySelector("#simpan")
                hide = document.querySelector("#hide")
    
                // Change Atribute
                bonus.removeAttribute("disabled")
                bonus.removeAttribute("readonly")
    
                date.removeAttribute("disabled")
                date.removeAttribute("readonly")
    
                percent.removeAttribute("disabled")
                percent.removeAttribute("readonly")
    
                // Change Class
                hide.classList.add("d-none")
    
                // Change Class
                save.classList.remove("d-none")

                // Remove Percent
                percent.value = percent.value.replace('%','')

                // Change type from text to number
                percent.type = 'number'
            })
        }

        bonus.addEventListener('change', e => {
            const bulanan = document.querySelector("#bulanan").selected;
            const tahunan = document.querySelector("#tahunan").selected;
                       
            if(bulanan)
            {
                formatDate = "d"

            }else if(tahunan)
            {
                formatDate = "m-d"
            }
            
            flatpickr(document.getElementsByClassName('isdate'), {
                defaultDate:  @json($config_date),
                dateFormat: formatDate,
                allowInput: true,
                // minDate: "today"
            });

            // Change Class
            tanggal.classList.remove("d-none")

            if(bulanan)
            {   
                // Erasing months
                document.querySelector('div.flatpickr-months').classList.add('d-none')

            }
        })

        function format(obj)
        {
            // Remove any non-number characters
            var f = obj.value.replace(/\D/g, '');
            // Length of the nomber string
            var l = f.length;
            // Check to see if we have uneven thousands, eg: 12,345
            var g = l % 3;

            // If even, then simply do the appropiate match
            if (g == 0)
                obj.value = thousands(f);
            // If uneven, store the lead
            else
            {
                // Lead is either the first 1 or 2 numbers
                var lead = f.substring(0, g);
                // Remove from the string we are going to match
                    f = f.substring(g, l);

                // Join everything nicely to display
                obj.value = lead + sep + thousands(f);
            }
        }

        // Function that commatizes the thousands
        function thousands(s)
        {
            // Match groups of 3 decimals
            var t = s.match(/(\d{3})/g);
            if (t) {
                return t.join(sep);
            } else {
                return '';
            }
        }

        function getBankDetail(sel) {
            var opt = sel.options[sel.selectedIndex];
            var name = opt.dataset.name;
            var code = opt.dataset.code;
            var accountNumber = opt.dataset.accountnumber;
            var accountHolderName = opt.dataset.accountholdername;
            $('input[name=bank_code]').val(code);
            $('input[name=bank_name]').val(name);
            $('input[name=bank_account_holder_name]').val(accountHolderName);
            $('input[name=bank_account_number]').val(accountNumber);
        }

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
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Pembayaran Bonus') }}</span></li>
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
                        <form method="POST" action="{{ action('Payment\BonusController@storeBonusConfig') }}">
                            @csrf
                            <div class="form-row">
                                @if($config_date !== null)
                                <div class="form-group col-sm-12 col-lg-2">
                                    <select class="custom-select" name="bonus_type" id="bonus" disabled="true" required>
                                        <option value="">--Bonus--</option>
                                        <option id="bulanan" value="bulanan" @if($config_type->value == 'bulanan') selected @endif>Bulanan</option>
                                        <option id="tahunan" value="tahunan" @if($config_type->value == 'tahunan') selected @endif>Tahunan</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2 d-none" id="tanggal">
                                    <input name="bonus_date" type="date" class="form-control form-control-sm isdate" placeholder="Tanggal..." @if(Request::filled('payment_date')) value="{{ Request::input('payment_date') }}" @endif id="bonus_date" disabled="true">
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <input name="bonus_percent" type="text" class="form-control" placeholder="Persen Bonus" value="{{ $config_percent->value }}%" required id="bonus_percent" disabled="true">
                                </div>
                                <div class="form-group col-sm-12 col-lg-2" id="hide">
                                    <button class="btn btn-success btn-block" id="ubah">{{ __('Ubah') }}</button>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2 d-none" id="simpan">
                                    <button class="btn btn-success btn-block" type="submit">{{ __('Save') }}</button>
                                </div>
                                @endif
                                @if($config_date == null)
                                <div class="form-group col-sm-12 col-lg-2">
                                    <select class="custom-select" name="bonus_type" id="bonus" required>
                                        <option value="">--Bonus--</option>
                                        <option id="bulanan" value="bulanan" @if($config_type->value == 'bulanan') selected @endif>Bulanan</option>
                                        <option id="tahunan" value="tahunan" @if($config_type->value == 'tahunan') selected @endif>Tahunan</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2 d-none" id="tanggal">
                                    <input name="bonus_date" type="date" class="form-control form-control-sm isdate" placeholder="Tanggal..." @if(Request::filled('payment_date')) value="{{ Request::input('payment_date') }}" @endif required>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <input name="bonus_percent" type="number" class="form-control" placeholder="Persen Bonus" value="{{ $config_percent->value }}" required>
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
                                {{-- <td>
                                    <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#transfer{{ $employe->id }}">{{ __('TRANSFER BONUS') }}</button>
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
                    <h5 class="modal-title" id="exampleModalLongTitle">TRANSFER BONUS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('Payment\DisbursementController@store') }}">
                    @csrf
                    <input type="hidden" name="employe_id" value="{{ $employe->id }}">
                    <input type="hidden" name="category" value="{{ \App\Models\Disbursement::BONUS }}">
                    <input type="hidden" name="to_name" value="{{ $employe->name }}">
                    <input type="hidden" name="to_email" value="{{ $employe->email }}">
                    <input type="hidden" name="bank_code" value="">
                    <input type="hidden" name="bank_name" value="">
                    <input type="hidden" name="bank_account_holder_name" value="">
                    <input type="hidden" name="bank_account_number" value="">
                    <div class="modal-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td>{{ $employe->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="name">Bank Tujuan</label>
                            <select class="custom-select" onchange="getBankDetail(this)">
                                <option value="" selected disabled>--Pilih--</option>
                                @foreach ($employe->employebanks as $bank)
                                    <option value="{{ $bank->id }}" data-code="{{ $bank->code }}" data-name="{{ $bank->name }}" data-accountnumber="{{ $bank->account_number }}" data-accountholdername="{{ $bank->account_holder_name }}">{{ $bank->name }} - {{ $bank->account_number }} - {{ $bank->account_holder_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Nominal</label>
                            <input name="amount" type="text" class="form-control" onkeyup="format(this)" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Keterangan</label>
                            <input name="description" type="text" class="form-control" value="Bonus {{ $employe->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">PIN</label>
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

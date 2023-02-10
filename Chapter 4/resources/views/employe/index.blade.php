@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="/themes/cork/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .flatpickr-calendar {
            z-index: 999999999 !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/flatpickr/flatpickr.js"></script>
    <script src="/themes/cork/plugins/select2/select2.min.js"></script>
    <script type="text/javascript">
        function addDate(dt, amount, dateType) {
          switch (dateType) {
            case 'days':
              return dt.setDate(dt.getDate() + amount) && dt;
            case 'weeks':
              return dt.setDate(dt.getDate() + (7 * amount)) && dt;
            case 'months':
              return dt.setMonth(dt.getMonth() + amount) && dt;
            case 'years':
              return dt.setFullYear( dt.getFullYear() + amount) && dt;
          }
        }
        let dt = new Date();
        dt = addDate(dt, -17, 'years');
        flatpickr(document.getElementsByClassName('isdate'), {
            maxDate: dt
        });
        $('.branch').each(function() {
            var $p = $(this).parent();
            $(this).select2({
                placeholder: 'Cari Cabang',
                minimumInputLength: 3,
                dropdownParent: $p,
                ajax: {
                    url: '/api/branches',
                    dataType: 'json'
                }
            });
        });

        var sep = '.';

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
    </script>
@endpush
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Karyawan') }}</span></li>
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
            <!-- Button trigger modal -->
            <div class="text-right">
                <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#createNew">
                    Buat Baru
                </button>
            </div>
            <form class="form-inline my-2 my-lg-0" method="GET">
                <div>
                    <input name="name" type="text" class="form-control product-search" id="input-search" placeholder="Cari Karyawan..." @if(Request::filled('name')) value="{{ Request::input('name') }}" @endif required>
                </div>
            </form>
            <div class="my-4">
                @include('layouts._partials.alert')
            </div>
            <div class="modal fade" id="createNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Buat Baru</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ action('Employe\EmployeController@store') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input name="name" type="text" class="form-control" id="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control" id="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Perusahaan</label>
                                    <select class="form-control" name="company_id" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($companies as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="branch">Cabang</label>
                                    <select class="form-control branch" name="branch_id" required></select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Departemen</label>
                                    <select class="form-control" name="department_id" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($departments as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Jabatan</label>
                                    <select class="form-control" name="position_id" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($positions as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="monthly_salary">Gaji /bulan</label>
                                    <input name="monthly_salary" type="text" class="form-control" id="monthly_salary" onkeyup="format(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="nik">NIK <small>(opsional)</small></label>
                                    <input name="nik" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="address">Alamat Lengkap <small>(opsional)</small></label>
                                    <textarea class="form-control" aria-label="With textarea" name="address"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="date_of_birth">Tanggal Lahir <small>(opsional)</small></label>
                                    <input name="date_of_birth" type="text" class="form-control isdate" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="place_of_birth">Tempat Lahir <small>(opsional)</small></label>
                                    <input name="place_of_birth" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="phone">No. HP <small>(opsional)</small></label>
                                    <input name="phone" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
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
                            <th scope="col">Gaji /bulan</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $company)
                            <tr>
                                <th scope="row">{{ $company->id }}</th>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->company->name }}</td>
                                <td>{{ $company->branch->name }}</td>
                                <td>{{ $company->department->name }}</td>
                                <td>{{ $company->position->name }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($company->monthly_salary) }}</td>
                                <td>
                                    <ul class="table-controls">
                                        <li>
                                            <a href="{{ route('bank.index', ['id' => $company->id]) }}" title="BANK">
                                                <i class="far fa-university"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" title="EDIT" data-toggle="modal" data-target="#edit{{ $company->id }}">
                                                <i class="fas fa-pencil"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" title="DELETE" data-toggle="modal" data-target="#delete{{ $company->id }}">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{
                $data->appends([
                    'name' => Request::filled('name') ? Request::input('name') : null
                ])->links()
            }}
        </div>
    </div>
</div>

@foreach ($data as $company)
    {{-- Edit Modal --}}
    <div class="modal fade" id="edit{{ $company->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('Employe\EmployeController@update', ['id' => $company->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input value="{{ $company->name }}" name="name" type="text" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input value="{{ $company->email }}" name="email" type="email" class="form-control" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Perusahaan</label>
                            <select class="form-control" name="company_id" required>
                                <option value="">--Pilih--</option>
                                @foreach ($companies as $c)
                                    <option value="{{ $c->id }}" @if($company->company_id === $c->id) selected @endif>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="branch">Cabang</label>
                            <select class="form-control branch" name="branch_id" required>
                                <option value="{{ $company->branch_id }}">{{ $company->branch->name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Departemen</label>
                            <select class="form-control" name="department_id" required>
                                <option value="">--Pilih--</option>
                                @foreach ($departments as $d)
                                    <option value="{{ $d->id }}" @if($company->department_id === $d->id) selected @endif>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Jabatan</label>
                            <select class="form-control" name="position_id" required>
                                <option value="">--Pilih--</option>
                                @foreach ($positions as $p)
                                    <option value="{{ $p->id }}" @if($company->position_id === $p->id) selected @endif>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="monthly_salary">Gaji /bulan</label>
                            <input value="{{ \App\Helpers\CurrencyHelper::toIDR((int)$company->monthly_salary) }}" name="monthly_salary" type="text" class="form-control" id="monthly_salary" onkeyup="format(this)" required>
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK <small>(opsional)</small></label>
                            <input value="{{ $company->nik }}" name="nik" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat Lengkap <small>(opsional)</small></label>
                            <textarea class="form-control" aria-label="With textarea" name="address">{!! $company->address !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Tanggal Lahir <small>(opsional)</small></label>
                            <input value="{{ $company->date_of_birth }}" name="date_of_birth" type="text" class="form-control isdate" readonly>
                        </div>
                        <div class="form-group">
                            <label for="place_of_birth">Tempat Lahir <small>(opsional)</small></label>
                            <input value="{{ $company->place_of_birth }}" name="place_of_birth" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone">No. HP <small>(opsional)</small></label>
                            <input value="{{ $company->phone }}" name="phone" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Delete --}}
    <div class="modal fade" id="delete{{ $company->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">HAPUS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('Employe\EmployeController@destroy', ['id' => $company->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Karyawan</label>
                            <input value="{{ $company->name }}" name="name" type="text" class="form-control" id="name" readonly>
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

@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="/themes/cork/plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/select2/select2.min.js"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $('.selectCode').on('change', function (e) {
                var optionSelected = $(this).find("option:selected");
                var nameSelected = optionSelected.data('name');
                $('input[name=name]').val(nameSelected);
            });
            $('.banks').each(function() {
                var $p = $(this).parent();
                $(this).select2({
                    placeholder: 'Cari Bank',
                    minimumInputLength: 2,
                    dropdownParent: $p
                });
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
                    <li class="breadcrumb-item"><a href="{{ url('/employes') }}">{{ __('Karyawan') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Bank') }}</span></li>
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
            <div class="my-4">
                @include('layouts._partials.alert')
            </div>
            <h4>List Bank - {{ $employe->name }}</h4>
            <div class="modal fade" id="createNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Buat Baru</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('bank.store', ['id' => $employe->id]) }}">
                            @csrf
                            <input type="hidden" name="employe_id" value="{{ $employe->id }}">
                            <input type="hidden" name="name" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="code">Bank</label>
                                    <select class="form-control selectCode banks" name="code" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($banks->data as $c)
                                            @if ($c->can_disburse)
                                                <option value="{{ $c->code }}" data-name="{{ $c->name }}">{{ $c->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="account_holder_name">Atas Nama</label>
                                    <input value="{{ $employe->name }}" name="account_holder_name" type="text" class="form-control" id="account_holder_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="account_number">Nomor Rekening</label>
                                    <input name="account_number" type="number" class="form-control" id="account_number" required>
                                </div>
                                <div class="form-group">
                                    <label for="code">Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="">--Pilih--</option>
                                        <option value="ACTIVE">ACTIVE</option>
                                        <option value="INACTIVE">INACTIVE</option>
                                    </select>
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
                            <th scope="col">Bank</th>
                            <th scope="col">Atas Nama</th>
                            <th scope="col">Nomor Rekening</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $company)
                            <tr>
                                <th scope="row">{{ $company->id }}</th>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->account_holder_name }}</td>
                                <td>{{ $company->account_number }}</td>
                                <td>
                                    <ul class="table-controls">
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
                $data->links()
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
                <form method="POST" action="{{ route('bank.update', ['id' => $employe->id, 'bankId' => $company->id]) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="employe_id" value="{{ $employe->id }}">
                    <input type="hidden" name="name" value="{{ $company->name }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="code">Bank</label>
                            <select class="form-control selectCode banks" name="code" required>
                                <option value="">--Pilih--</option>
                                @foreach ($banks->data as $c)
                                    @if ($c->can_disburse)
                                        <option value="{{ $c->code }}" data-name="{{ $c->name }}" @if($company->code === $c->code) selected @endif>{{ $c->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="account_holder_name">Atas Nama</label>
                            <input value="{{ $company->account_holder_name }}" name="account_holder_name" type="text" class="form-control" id="account_holder_name" required>
                        </div>
                        <div class="form-group">
                            <label for="account_number">Nomor Rekening</label>
                            <input value="{{ $company->account_number }}" name="account_number" type="number" class="form-control" id="account_number" required>
                        </div>
                        <div class="form-group">
                            <label for="code">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="">--Pilih--</option>
                                <option value="ACTIVE" @if($company->status === 'ACTIVE') selected @endif>ACTIVE</option>
                                <option value="INACTIVE" @if($company->status === 'INACTIVE') selected @endif>INACTIVE</option>
                            </select>
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
                <form method="POST" action="{{ route('bank.delete', ['id' => $employe->id, 'bankId' => $company->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Bank</label>
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

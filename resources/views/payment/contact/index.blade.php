@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="/themes/cork/plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/select2/select2.min.js"></script>
    <script type="text/javascript">

        $('.selectCode').on('change', function (e) {
            var optionSelected = $(this).find("option:selected");
            var nameSelected = optionSelected.data('name');
            $('input[name=bank_name]').val(nameSelected);
        });

        $('.banks').each(function() {
            var $p = $(this).parent();
            $(this).select2({
                placeholder: 'Cari Bank',
                minimumInputLength: 2,
                dropdownParent: $p
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
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Contact') }}</span></li>
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
            @if (!Auth::user()->hasRole('root'))
            <div class="text-right">
                <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#createContact">
                    Tambah Kontak
                </button>
            </div>
            @endif
            <form class="form-inline my-2 my-lg-0" method="GET">
                <div>
                    <input name="name" type="text" class="form-control product-search" id="input-search" placeholder="Cari Kontak..." @if(Request::filled('name')) value="{{ Request::input('name') }}" @endif>
                </div>
            </form>
            <div class="my-4">
                @include('layouts._partials.alert')
            </div>

            <div class="modal fade" id="createContact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Tambah Data</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ action('ContactController@store') }}">
                            @csrf
                            <input type="hidden" name="account_code" value="{{ $slug }}">
                            <input type="hidden" name="bank_name" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input name="name" type="text" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="bank_code">Bank</label>
                                    <select class="form-control selectCode banks" name="bank_code" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($banks->data as $c)
                                            @if ($c->can_disburse)
                                                <option value="{{ $c->code }}" data-name="{{ $c->name }}">{{ $c->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="bank_account_holder_name">Atas Nama</label>
                                    <input value="" name="bank_account_holder_name" type="text" class="form-control" id="bank_account_holder_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="bank_account_number">Nomor Rekening</label>
                                    <input name="bank_account_number" type="number" class="form-control" id="bank_account_number" required>
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
                            <th scope="col">Nama Kontak</th>
                            <th scope="col">Email</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $contact)
                            <tr>
                                <th scope="row">{{ $contact->id }}</th>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>
                                    <ul class="table-controls">
                                        <li>
                                            <a href="javascript:void(0);" title="EDIT" data-toggle="modal" data-target="#edit{{ $contact->id }}">
                                                <i class="fas fa-pencil"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" title="DELETE" data-toggle="modal" data-target="#delete{{ $contact->id }}">
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

@foreach ($data as $item)
    {{-- Edit --}}
    <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('ContactController@update', ['id' => $item->id]) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="account_code" value="{{ $slug }}">
                    <input type="hidden" name="bank_name" value="{{ $item->bank_name }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input name="name" type="text" class="form-control" value="{{ $item->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control" value="{{ $item->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="bank_code">Bank</label>
                            <select class="form-control selectCode banks" name="bank_code" required>
                                <option value="">--Pilih--</option>
                                @foreach ($banks->data as $c)
                                    @if ($c->can_disburse)
                                        <option value="{{ $c->code }}" data-name="{{ $c->name }}" @if($item->bank_code === $c->code) selected @endif>{{ $c->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bank_account_holder_name">Atas Nama</label>
                            <input value="{{ $item->bank_account_holder_name }}" name="bank_account_holder_name" type="text" class="form-control" id="bank_account_holder_name" required>
                        </div>
                        <div class="form-group">
                            <label for="bank_account_number">Nomor Rekening</label>
                            <input value="{{ $item->bank_account_number }}" name="bank_account_number" type="number" class="form-control" id="bank_account_number" required>
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
    <div class="modal fade" id="delete{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('ContactController@destroy', ['id' => $item->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        Hapus <b>{{ $item->name }}</b> ?
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

@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
@endpush
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Invoice Contact') }}</span></li>
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
                    Tambah Kontak
                </button>
            </div>
            <form class="form-inline my-2 my-lg-0" method="GET">
                <div>
                    <input name="name" type="text" class="form-control product-search" id="input-search" placeholder="Cari Kontak..." @if(Request::filled('name')) value="{{ Request::input('name') }}" @endif required>
                </div>
            </form>
            <div class="my-4">
                @include('layouts._partials.alert')
            </div>
            <div class="modal fade" id="createNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Tambah Kontak</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ action('Invoice\InvoiceContactController@store') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input name="name" type="text" class="form-control" id="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="text" class="form-control" id="email">
                                </div>
                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <textarea name="address" type="text" class="form-control" id="address"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input name="phone" type="text" class="form-control" id="phone">
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
                            <th scope="col">Nomor Telepon</th>
                            <th scope="col">Email</th>
                            <th scope="col">Alamat</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $invoiceContact)
                            <tr>
                                <th scope="row">{{ $invoiceContact->id }}</th>
                                <td>{{ $invoiceContact->name }}</td>
                                <td>{{ $invoiceContact->phone }}</td>
                                <td>{{ $invoiceContact->email }}</td>
                                <td>{{ $invoiceContact->address }}</td>
                                <td>
                                    <ul class="table-controls">
                                        <li>
                                            <a href="javascript:void(0);" title="EDIT" data-toggle="modal" data-target="#edit{{ $invoiceContact->id }}">
                                                <i class="fas fa-pencil"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" title="DELETE" data-toggle="modal" data-target="#delete{{ $invoiceContact->id }}">
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

@foreach ($data as $invoiceContact)
    {{-- Edit Modal --}}
    <div class="modal fade" id="edit{{ $invoiceContact->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('Invoice\InvoiceContactController@update', ['id' => $invoiceContact->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input value="{{ $invoiceContact->name }}" name="name" type="text" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input value="{{ $invoiceContact->email }}" name="email" type="text" class="form-control" id="email">
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea name="address" type="text" class="form-control" id="address">{{ $invoiceContact->address }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input value="{{ $invoiceContact->phone }}" name="phone" type="text" class="form-control" id="phone">
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
    <div class="modal fade" id="delete{{ $invoiceContact->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">HAPUS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('Invoice\InvoiceContactController@destroy', ['id' => $invoiceContact->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input value="{{ $invoiceContact->name }}" name="name" type="text" class="form-control" id="name" readonly>
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

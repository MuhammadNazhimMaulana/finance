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
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Admin') }}</span></li>
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
                    <input name="name" type="text" class="form-control product-search" id="input-search" placeholder="Cari Admin..." @if(Request::filled('name')) value="{{ Request::input('name') }}" @endif required>
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
                        <form method="POST" action="{{ action('AdminController@store') }}">
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
                            <th scope="col">Email</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $company)
                            <tr>
                                <th scope="row">{{ $company->id }}</th>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->email }}</td>
                                <td>
                                    @if ($company->deleted_at)
                                        <span class="badge badge-pill badge-danger">DELETED</span>
                                    @else
                                        <span class="badge badge-pill badge-success">ACTIVE</span>
                                    @endif
                                </td>
                                <td>
                                    <ul class="table-controls">
                                        @if (!$company->deleted_at)
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
                                        @else
                                            <li>
                                                <a href="javascript:void(0);" title="RESTORE" data-toggle="modal" data-target="#restore{{ $company->id }}">
                                                    <i class="far fa-trash-restore"></i>
                                                </a>
                                            </li>
                                        @endif
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
                <form method="POST" action="{{ action('AdminController@update', ['id' => $company->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input name="name" type="text" class="form-control" id="name" value="{{ $company->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control" id="email" value="{{ $company->email }}" required>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="yes" id="defaultCheck1" name="reset">
                                <label class="form-check-label" for="defaultCheck1">
                                    Reset Password
                                </label>
                            </div>
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
                <form method="POST" action="{{ action('AdminController@destroy', ['id' => $company->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
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
    {{-- Restore --}}
    <div class="modal fade" id="restore{{ $company->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">RESTORE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('AdminController@restore', ['id' => $company->id]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input value="{{ $company->name }}" name="name" type="text" class="form-control" id="name" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">RESTORE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

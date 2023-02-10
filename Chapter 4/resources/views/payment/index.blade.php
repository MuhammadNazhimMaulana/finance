@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="/themes/cork/plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/select2/select2.min.js"></script>
    <script type="text/javascript">
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
                    <li class="breadcrumb-item active" aria-current="page"><span>Pembayaran {{ ucfirst(trans($slug)) }}</span></li>
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
                    <div class="col-md-12 col-lg-6">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-sm-12 col-lg-8">
                                    <input name="name" type="text" class="form-control form-control-sm" placeholder="Nama..." @if(Request::filled('name')) value="{{ Request::input('name') }}" @endif>
                                </div>
                                <div class="form-group col-sm-12 pt-1 col-lg-4">
                                    <button class="btn btn-primary btn-block" type="submit">{{ __('Filter') }}</button>
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
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">Bank</th>
                            <th scope="col">No. Rekening</th>
                            <th scope="col">Atas Nama</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->bank_name }}</td>
                                <td>{{ $item->bank_account_number }}</td>
                                <td>{{ $item->bank_account_holder_name }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#transfer{{ $item->id }}">{{ __('TRANSFER') }}</button>
                                    </div>
                                    {{-- <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#transfer{{ $item->id }}">{{ __('TRANSFER') }}</button> --}}
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
    {{-- Transfer Modal --}}
    <div class="modal fade" id="transfer{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">TRANSFER</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('Payment\DisbursementController@store') }}">
                    @csrf
                    <input type="hidden" name="category" value="{{ $slug }}">
                    <input type="hidden" name="to_name" value="{{ $item->name }}">
                    <input type="hidden" name="to_email" value="{{ $item->email }}">
                    <input type="hidden" name="bank_code" value="{{ $item->bank_code }}">
                    <input type="hidden" name="bank_name" value="{{ $item->bank_name }}">
                    <input type="hidden" name="bank_account_holder_name" value="{{ $item->bank_account_holder_name }}">
                    <input type="hidden" name="bank_account_number" value="{{ $item->bank_account_number }}">
                    <div class="modal-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td>{{ $item->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bank</td>
                                        <td>:</td>
                                        <td>{{ $item->bank_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>No. Rekening</td>
                                        <td>:</td>
                                        <td>{{ $item->bank_account_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Atas Nama</td>
                                        <td>:</td>
                                        <td>{{ $item->bank_account_holder_name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="amount">Nominal</label>
                            <input name="amount" type="text" class="form-control" onkeyup="format(this)" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Keterangan</label>
                            <input name="description" type="text" class="form-control" value="Pembayaran {{ ucfirst(trans($slug)) }} {{ $item->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="pin">PIN</label>
                            <input name="pin" type="password" class="form-control" maxlength="6" minlength="6" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success showownloading" data-mid="transfer{{ $item->id }}">KONFIRMASI</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

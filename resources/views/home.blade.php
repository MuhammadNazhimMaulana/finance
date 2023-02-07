@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/plugins/loaders/custom-loader.css">
@endpush
@push('scripts')
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
    </script>
@endpush
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Beranda') }}</span></li>
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
            <div class="my-4">
                @include('layouts._partials.alert')
                @if (session('invoiceurl'))
                    <div class="alert alert-light-success border-0 mb-4" role="alert">
                        LINK PEMBAYARAN: <a href="{{ session('invoiceurl') }}" target="_Blank">{{ session('invoiceurl') }}</a>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#topup">
                TOPUP
            </button>
            <div class="modal fade" id="topup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">TOPUP</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ action('Payment\TopupController@store') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="amount">NOMINAL</label>
                                    <input name="amount" type="text" class="form-control" onkeyup="format(this)" required>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="payment_method">{{ __('Payment Channel') }}</label>
                                    <select class="custom-select" name="payment_method" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($payment_channels->data as $d)
                                            <option value="{{ $d->channel_code }}">{{ $d->channel_code }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success" data-mid='topup'>KONFIRMASI</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <general-root-balance></general-root-balance>
</div>
@endsection

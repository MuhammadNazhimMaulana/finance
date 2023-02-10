@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/plugins/loaders/custom-loader.css">
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

    // Check checked other nominal
    $('.other-donate').hide();
    $('#other').on('change', function() {
       $('.other-donate').show();
    });
    $('.fix-price').on('change', function() {
       $('.other-donate').hide();
    });
</script>
@endpush
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Donasi') }}</span></li>
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
    <div>
        <div class="card component-card_1 text-center mb-5">
            <div class="card-body">
                <h1>
                    <svg class="svg-inline--fa fa-wallet fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="wallet" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M461.2 128H80c-8.84 0-16-7.16-16-16s7.16-16 16-16h384c8.84 0 16-7.16 16-16 0-26.51-21.49-48-48-48H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h397.2c28.02 0 50.8-21.53 50.8-48V176c0-26.47-22.78-48-50.8-48zM416 336c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"></path>
                    </svg>
                </h1>
                <h5 class="card-title">TOTAL DONASI</h5>
                <h3 class="text-success">Rp{{ \App\Helpers\CurrencyHelper::toIDR($total_donation) }}</h3>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-dark">
                <thead class="thead-dark|thead-light">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Nama Donasi</th>
                        <th scope="col" class="text-center">Donatur</th>
                        <th scope="col" class="text-center">Penanggung Jawab</th>
                        <th scope="col" class="text-center">Nominal</th>
                        <th scope="col" class="text-center">Fee</th>
                        <th scope="col" class="text-center">Total</th>
                        <th scope="col" class="text-center">Tanggal Transfer</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $donation)
                        <tr>
                            <td class="text-center">{{ $donation->id }}</td>
                            <td class="text-center">{{ $donation->deskripsi_donasi }}</td>
                            <td class="text-center">{{ $donation->donor_name }}</td>
                            <td class="text-center">{{ $donation->person_responsible_name }}</td>
                            <td class="text-center">{{ \App\Helpers\CurrencyHelper::toIDR($donation->grand_total_amount) }}</td>
                            <td class="text-center">{{ \App\Helpers\CurrencyHelper::toIDR($donation->fee) }}</td>
                            <td class="text-center">{{ \App\Helpers\CurrencyHelper::toIDR($donation->amount) }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($donation->created_at)->format('d F Y') }}</td>
                            <td class="text-center">{{ $donation->status }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#detailTransaksi{{ $donation->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{
            $data->appends([
                'status' => Request::filled('status') ? Request::input('status') : null
            ])->links()
        }}
    </div>
</div>

@foreach ($data as $item)
    {{-- Modal --}}
    <div class="modal fade" id="detailTransaksi{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Detail Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="deskripsi_donasi">Nama Donasi</label>
                        <input name="deskripsi_donasi" type="text" class="form-control" id="deskripsi_donasi" value="{{ $item->deskripsi_donasi }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="donatur">Donatur</label>
                        <input name="donatur" type="text" class="form-control" id="donatur" value="{{ $item->donor_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="donor_bank">Bank Donatur</label>
                        <input name="donor_bank" type="text" class="form-control" id="donor_bank" value="{{ $item->donor_bank }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="donor_bank_number">Nomor Rekening Donatur</label>
                        <input name="donor_bank_number" type="text" class="form-control" id="donor_bank_number" value="{{ $item->donor_bank_number }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="perosn_responsible">Penanggung Jawab</label>
                        <input name="perosn_responsible" type="text" class="form-control" id="perosn_responsible" value="{{ $item->person_responsible_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input name="nominal" type="text" class="form-control" id="nominal" value="{{ \App\Helpers\CurrencyHelper::toIDR($item->amount) }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="created_at">Tanggal Transfer</label>
                        <input name="created_at" type="text" class="form-control" id="created_at" value="{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <input name="status" type="text" class="form-control" id="status" value="{{ $item->status }}" readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

                    {{-- If Still Pending --}}
                    @if ($item->status === \App\Models\Topup::STATUS_PENDING)
                        <a class="btn btn-success btn-md" href="{{ $item->payment_url }}" target="_Blank">{{ __('Bayar') }}</a>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection

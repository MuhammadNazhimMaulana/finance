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
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('History Invoice') }}</span></li>
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
            <form class="form-inline my-2 my-lg-0" method="GET">
                <div>
                    <input name="name" type="text" class="form-control product-search" id="input-search" placeholder="Cari Invoice..." @if(Request::filled('name')) value="{{ Request::input('name') }}" @endif>
                </div>
            </form>
            <div class="my-4">
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-dark">
                    <thead class="thead-dark|thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Kode Invoice</th>
                            <th scope="col">Dari</th>
                            <th scope="col">Kepada</th>
                            <th scope="col">Nominal</th>
                            <th scope="col">Fee</th>
                            <th scope="col">Total</th>
                            <th scope="col">Tanggal Pembuatan</th>
                            <th scope="col">Expired</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->external_id }}</td>
                                <td>{{ $invoice->company_name }}</td>
                                <td>{{ $invoice->contact_name }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($invoice->amount) }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($invoice->fee) }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::toIDR($invoice->grand_total_amount) }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->expired_at)->format('d F Y') }}</td>
                                <td>{{ $invoice->status }}</td>
                                <td>
                                    {{-- @if ($invoice->status === 'PENDING')
                                        <a class="btn btn-success" href="{{ $invoice->payment_url }}" target="_blank">Link</a>
                                    @endif --}}

                                    <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#detailInvoice{{ $invoice->id }}">
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
</div>

@foreach ($data as $item)
    {{-- Modal --}}
    <div class="modal fade" id="detailInvoice{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Detail Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <div class="table-responsive-sm">
                        <table class="table table-striped table-sm">
                            <tbody>
                                <tr>
                                    <td>Nama Item</td>
                                    <td>Jumlah Item</td>
                                    <td>Harga Satuan</td>
                                    <td>Harga Total</td>
                                </tr>

                                @php
                                    $final_total = [];
                                @endphp --}}

                                {{-- Looping For Item --}}
                                {{-- @foreach( json_decode($item->xendit_data)->data->items as $barang)
                                <tr>
                                    @php
                                        $total_price = $barang->price * $barang->quantity;
                                        array_push($final_total, $total_price);

                                        // Harga Akhir
                                        $final_price = array_sum($final_total);
                                    @endphp
                                    <td>{{ $barang->name }}</td>
                                    <td>{{ $barang->quantity }}</td>
                                    <td>{{ \App\Helpers\CurrencyHelper::toIDR($barang->price) }}</td>
                                    <td>{{ \App\Helpers\CurrencyHelper::toIDR($barang->quantity * $barang->price) }}</td>
                                </tr>
                                @endforeach --}}
                                {{-- End Looping --}}

                                {{-- <tr>
                                    <td></td>
                                    <td></td>
                                    <td>Jumlah</td>
                                    <td>{{ \App\Helpers\CurrencyHelper::toIDR($final_price) }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div> --}}
                    <div class="form-group">
                        <label for="external_id">Kode Invoice</label>
                        <input name="external_id" type="text" class="form-control" id="external_id" value="{{ $item->external_id }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="company_name">Dari</label>
                        <input name="company_name" type="text" class="form-control" id="company_name" value="{{ $item->company_name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="contact_name">Kepada</label>
                        <input name="contact_name" type="text" class="form-control" id="contact_name" value="{{ $item->contact_name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input name="status" type="text" class="form-control" id="status" value="{{ $item->status }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="grand_total_amount">Total Pembayaran</label>
                        <input name="grand_total_amount" type="text" class="form-control" id="grand_total_amount" value="{{ \App\Helpers\CurrencyHelper::toIDR($item->grand_total_amount) }}" readonly>
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

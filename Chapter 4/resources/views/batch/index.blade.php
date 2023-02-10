@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/plugins/loaders/custom-loader.css">
    <link href="/themes/cork/plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
<script src="/themes/cork/plugins/select2/select2.min.js"></script>
<script type="text/javascript">
    
    $( document ).ready(function() {
        const select2Contacts = $('.contacts').select2();
        let selectedContacts = [];
        let id = [];

        function addContact (e) {
            var optionSelected = $(e).find("option:selected");
            var nameSelected = optionSelected[optionSelected.length-1].getAttribute('data-name');
            var idSelected = optionSelected[optionSelected.length-1].getAttribute('data-id');

            // console.log($('.selectContact').val());
            if(!id.includes(idSelected)){
                id.push(idSelected);
                
                // Create Array of Object
                selectedContacts.push({
                    id: idSelected,
                    name: nameSelected,
                    nominal: 0
                });
            }

            // Calling Function
            renderContact(selectedContacts)
        };

        // Nominal
        $('#filter-container').on('keyup', '.action-nominal', function (e){
            let index = $(this).data('key');
            let value = $(this).val();

            // Assign value to nominal
            selectedContacts[index].nominal = value;
        });

        // Deleting
        $('#filter-container').on('click', '.action-close', function (e){
            let index = $(this).data('contact');
            let deletedId = $(this).data('id');
            let indexOfId = id.indexOf(`${deletedId}`);

            // let value = $(`div[id=person${selectedContacts[index].id}]`);

            // Delete Array Based on index
            selectedContacts.splice(index, 1);

            // Splicing Id
            id.splice(indexOfId, 1);

            // Recall
            let idSelectedContact = renderContact(selectedContacts)
            
            // Calling Select 2 change value
            changeValueSelect2(select2Contacts, idSelectedContact);

        });

        // Render Contact List
        function renderContact(contacts){
            let id = [];
            let html = contacts.map((item, index) => {
                id.push(item.id);
                return `<div class="row align-items-center filter" id="person${item.id}">
                            <div class="col-md-4 mb-3 check-container">
                                <input class="checkbox-contact" type="checkbox" id="contact${item.id}" name="contact${item.id}" value="${item.name}" checked style="opacity:0; position: absolute; top:0; left: 0;">
                                <label for="contact${item.id}" class="name mb-0">${item.name}</label>
                            </div>

                            <div class="col-md-6 mb-3">
                                <input placeholder="Nominal" name="total_fee${item.id}" type="text" class="form-control action-nominal" id="total_fee${item.id}" onkeyup="format(this)" data-key="${index}" value="${item.nominal}">
                            </div>

                            <div class="col-md-2 mb-3">
                                <button type="button" data-contact="${index}" data-id="${item.id}" class="btn btn-danger action-close">x</button>
                            </div>

                    </div>`
            })

            $('#filter-container').html(html);

            // Returning list of id
            return id;
        }

        // Changing the value of select2
        function changeValueSelect2(el, value){
            el.val(value).trigger("change");
        }
        
        $('.contacts').each(function() {
            var $p = $(this).parent();
            $(this).select2({
                placeholder: 'Cari Kontak', 
                minimumInputLength: 2,
                dropdownParent: $p
            });
        })
        
        // When Adding Contact
        .on('select2:select', function(e){
            var elm = e.params.data.element;
            $elm = jQuery(elm);
            $t = jQuery(this);
            $t.append($elm);
            $t.trigger('change.select2');
            addContact(this)
        })
        
        // Unselecting Contact
        .on('select2:unselect', function(e){
            let deletedId = e.params.data.id;
            let indexOfId = id.indexOf(`${deletedId}`);

            // Delete Array Based on index
            selectedContacts.splice(indexOfId, 1);

            // Splicing Id
            id.splice(indexOfId, 1);

            // Recall
            let idSelectedContact = renderContact(selectedContacts)
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
multiple="multiple"
@endpush
@section('breadcrumb')
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Transaksi') }}</span></li>
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
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 text-right">
        @if (!Auth::user()->hasRole('root'))
        <button type="button" class="btn btn-success mt-5" data-toggle="modal" data-target="#transaksi">
            Tambah Transaksi Transfer
        </button>
        @endif
        <div class="modal fade text-left" id="transaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ action('BatchController@store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="remark">Deskripsi</label>
                                <input name="remark" type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="contact">Kontak</label>

                                <select class="form-control selectContact contacts" name="code" multiple="multiple" required>
                                    <option value="">--Pilih--</option>
                                    @foreach ($contacts as $n)
                                        @if ($n->name)
                                            <option class="select-contact" value="{{ $n->id }}" data-name="{{ $n->name }}" data-id="{{ $n->id }}">{{ $n->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="row-container" id="filter-container"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">PIN</label>
                                <input name="pin" type="password" class="form-control" maxlength="6" minlength="6" required>
                            </div>
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
    <div>
        <div class="my-4">
            @include('layouts._partials.alert')
        </div>
        {{-- <div class="row layout-top-spacing" v-else>
            <div class="col-xs-12 col-md-4 mb-3">
                <div class="card component-card_1 text-center">
                    <div class="card-body">
                        <h1><i class="fas fa-wallet"></i></h1>
                        <h5 class="card-title">Pending</h5>
                        <h3 class="text-success">Rp.{{ $latest ? \App\Helpers\CurrencyHelper::toIDR($latest->pending_count) : 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4 mb-3">
                <div class="card component-card_1 text-center">
                    <div class="card-body">
                        <h1><i class="fas fa-hand-holding-heart"></i></h1>
                        <h5 class="card-title">Completed</h5>
                        <h3 class="text-success">Rp.{{ $latest ? \App\Helpers\CurrencyHelper::toIDR($latest->complete_count) : 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4 mb-3">
                <div class="card component-card_1 text-center">
                    <div class="card-body">
                        <h1><i class="fad fa-fire-smoke"></i></h1>
                        <h5 class="card-title">Failed</h5>
                        <h3 class="text-success">Rp.{{ $latest ? \App\Helpers\CurrencyHelper::toIDR($latest->failed_count) : 0 }}</h3>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="table-responsive">
            <table class="table table-striped table-dark">
                <thead class="thead-dark|thead-light">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Remark</th>
                        <th scope="col" class="text-center">Total Amount</th>
                        <th scope="col" class="text-center">Total Fee</th>
                        <th scope="col" class="text-center">Total</th>
                        <th scope="col" class="text-center">Tanggal Transfer</th>
                        <th scope="col" class="text-center">Total Pending</th>
                        <th scope="col" class="text-center">Total Completed</th>
                        <th scope="col" class="text-center">Total Failed</th>
                        <th scope="col" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $batch)
                        <tr>
                            <td class="text-center">{{ $batch->id }}</td>
                            <td class="text-center">{{ $batch->remark }}</td>
                            <td class="text-center">{{ \App\Helpers\CurrencyHelper::toIDR($batch->total_amount) }}</td>
                            <td class="text-center">{{ \App\Helpers\CurrencyHelper::toIDR($batch->total_fee) }}</td>
                            <td class="text-center">{{ \App\Helpers\CurrencyHelper::toIDR($batch->total) }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($batch->created_at)->format('d F Y') }}</td>
                            <td class="text-center">{{ $batch->pending_count }}</td>
                            <td class="text-center">{{ $batch->complete_count }}</td>
                            <td class="text-center">{{ $batch->failed_count }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#detailTransaksi{{ $batch->id }}">
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
                    <form method="POST" action="{{ action('BatchController@retransfer') }}">
                    @csrf
                    <div class="form-group">
                        <label for="remark">Deskripsi</label>
                        <input name="batch_id" type="hidden" class="form-control" value="{{ $item->id }}">
                        <input name="remark" type="text" class="form-control" id="remark" value="{{ $item->remark }}" readonly>
                    </div>

                    {{-- Contact and nominal --}}
                    <div class="form-group">
                        <label for="company_name">Tujuan</label>
                        <div class="row align-items-center">
                            @foreach($item->disbursements as $contact)
                            <div class="col-md-4 mb-3">
                                <input name="total_fee" type="text" class="form-control" value="{{ $contact->to_name }}"  id="total_fee" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input name="total_fee" type="text" class="form-control" value="{{ \App\Helpers\CurrencyHelper::toIDR($contact->amount) }}"  id="total_fee" readonly>
                            </div>
                            {{-- If Theere are Failed Disbursement --}}
                                <div class="col-md-4 mb-3">
                                    @if ($contact->status ==  App\Models\Disbursement::STATUS_COMPLETED )
                                        <a class="btn btn-info btn-md" href="{{ url('/batches/pdf/'. $contact->id ) }}" target="_Blank">{{ __('Print Bukti') }}</a>
                                    @else 
                                        <input name="status" type="text" class="form-control form-control-sm" value="{{ $contact->status }}"  id="status" readonly>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="total">Total Pembayaran</label>
                        <input name="total" type="text" class="form-control" id="total" value="{{ \App\Helpers\CurrencyHelper::toIDR($item->total_amount) }}" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    
                    {{-- If Theere are Failed Disbursement --}}
                    @if ($item->failed_count > 0)
                        <button type="submit" class="btn btn-success btn-md">{{ __('Transfer Ulang') }}</button>
                        {{-- <a class="btn btn-success btn-md" href="{{ url('/batches/retransfer') }}" target="_Blank">{{ __('Transfer Ulang') }}</a> --}}
                    @endif
                </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection

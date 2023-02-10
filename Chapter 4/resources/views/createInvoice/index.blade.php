@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="/themes/cork/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="/themes/cork/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/themes/cork/plugins/flatpickr/flatpickr.js"></script>
    <script type="text/javascript">
    flatpickr(document.getElementsByClassName('isdate'), {
        maxDate: new Date().fp_incr(365),
        minDate: "today",
        allowInput: true, // prevent "readonly" prop
        onReady: function(selectedDates, dateStr, instance) {
        let el = instance.element;
        function preventInput(event) {
            event.preventDefault();
            return false;
        };
        el.onkeypress = el.onkeydown = el.onkeyup = preventInput; // disable key events
        el.onpaste = preventInput; // disable pasting using mouse context menu

        el.style.caretColor = 'transparent'; // hide blinking cursor
        el.style.cursor = 'pointer'; // override cursor hover type text
    },
    });

    // Array for items
    const Items = [];
    
    // eventListener
    const addItem = document.querySelector("#addItem");

    addItem.addEventListener('submit', e => {
    e.preventDefault()

    if(document.getElementById('allItem').value)
    {
        document.getElementById('allItem').value = '';
    }
    
    // Nmae of Item
    let itemName = document.getElementById('item_name').value;
    
    // Price of Item
    let itemPrice = document.getElementById('item_price').value;
    
    // Amount of Item
    let itemAmount = document.getElementById('item_amount').value;
    
    // Item Object
    let itemData = {
        name: itemName,
        quantity: itemAmount,
        price: String(itemPrice).split('.').join('')
    };
    
    // Adding Value of Array 
    if(itemData.name == '' || itemData.price == '' || itemData.quantity == '')
    {   
        // Sending the data to hidden input
        document.getElementById('allItem').value = JSON.stringify(Items);
    }else{
        Items.push(itemData);
    
        // Sending the data to hidden input
        document.getElementById('allItem').value = JSON.stringify(Items);
        
        // console.log(Items);
        
        // Clean the forms for Item
        document.getElementById('item_name').value = '';
        document.getElementById('item_price').value = '';
        document.getElementById('item_amount').value = '1';
        
        //Running function to add input data to table
        refreshTable(Items);
        } 
    })

        function refreshTable(Items){

            if(Items.length > 1)
                {
                    const data_table_delete = document.getElementById('item_row');
                    // Preventing duplicate row
                    for(let i = 1; i < Items.length; i++){
                        deleteTable();
                }
            }

            // Delete duplicate row
            function deleteTable(){
                const data_table_delete = document.getElementById('item_row');
                data_table_delete.remove();
            }

            // Length of the array
            Items.forEach(myFunction)

            // Adding new row
            function myFunction(item, index){
            
                const data_table = document.getElementById('body-table');
               
                let row = data_table.insertRow();
                let number = row.insertCell(0);
                let item_name = row.insertCell(1);
                let harga = row.insertCell(2);
                let jumlah_beli = row.insertCell(3);
                let total_harga = row.insertCell(4);

                // Value for each cell
                row.id = "item_row"
                number.innerHTML = index + 1;
                item_name.innerHTML = item.name;
                harga.innerHTML = numberWithDots(item.price);
                jumlah_beli.innerHTML = item.quantity;
                total_harga.innerHTML = numberWithDots(item.quantity * item.price);

            }

        }

        //Formatting Number
        function numberWithDots(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

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
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Create Invoice') }}</span></li>
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
            <h4 class="mb-3">Membuat Invoice Baru</h4>
            <div class="mb-4 mt-4">
                @include('layouts._partials.alert')
                @if (session('invoiceurl'))
                    <div class="alert alert-light-success border-0 mb-4" role="alert">
                        LINK PEMBAYARAN: <a href="{{ session('invoiceurl') }}" target="_Blank">{{ session('invoiceurl') }}</a>
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <form method="POST" action="{{ action('Invoice\CreateInvoiceController@store') }}">
                            @csrf
                            {{-- Start of Select Company and Contact--}}
                            <div class="form-row align-items-end">
                                <div class="form-group col-sm-12 col-lg-2">
                                    <label>{{ __('Dari') }}</label>
                                    <select class="custom-select" name="company_id" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" @if(Request::filled('company_id') && Request::input('company_id') == $company->id) selected @endif>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <label>{{ __('Ke') }}</label>
                                    <select class="custom-select" name="invoice_contact_id" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($contacts as $contact)
                                            <option value="{{ $contact->id }}" @if(Request::filled('invoice_contact_id') && Request::input('invoice_contact_id') == $contact->id) selected @endif>{{ $contact->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <label for="payment_method">{{ __('Payment Channel') }}</label>
                                    <select class="custom-select" name="payment_method" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($payment_channels->data as $d)
                                            <option value="{{ $d->channel_code }}">{{ $d->channel_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- End of Select Company and Contact --}}
                                <div class="form-group col-sm-12 col-lg-3">
                                    <label>{{ __('Tanggal Kadaluarsa') }}</label>
                                    <input name="expired_at" type="text" class="form-control form-control-sm isdate" placeholder="Tanggal Kadaluarsa..." @if(Request::filled('expired_at')) value="{{ Request::input('expired_at') }}" @else value="{{ \Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}"  @endif required>
                                </div>

                                {{-- Create Invoice Button --}}
                                <div class="form-group col-sm-12 col-lg-2">
                                    <button type="submit" class="btn btn-success mb-1 w-100">
                                        Terbitkan Invoice
                                    </button>
                                </div>

                                <input type="hidden" name="Items" id="allItem">  
                            </div>

                        </form>

                        {{-- Item Input --}}
                        <form id="addItem">
                            <div class="form-row mt-5 align-items-end">
                                <div class="form-group col-sm-12 col-lg-4">
                                    <label>{{ __('Nama Item') }}</label>
                                    <input type="text" id="item_name" class="form-control form-control-sm" required>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <label>{{ __('Harga') }}</label>
                                    <input type="text" id="item_price" class="form-control form-control-sm" onkeyup="format(this)" required>
                                </div>
                                <div class="form-group col-sm-12 col-lg-3">
                                    <label>{{ __('Kuantitas') }}</small></label>
                                    <input type="number" id="item_amount" class="form-control form-control-sm" value="1" required>
                                </div>
                                <div class="form-group col-sm-12 col-lg-2">
                                    <button type="submit" class="btn btn-warning mb-1 w-100">
                                        Tambah Item
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Item Table --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-dark" id="table-item">
                                <thead class="thead-dark|thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Jumlah Beli</th>
                                        <th scope="col">Total Harga</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody id="body-table">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

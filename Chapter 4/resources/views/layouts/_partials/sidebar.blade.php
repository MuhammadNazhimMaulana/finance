@php( $accounts = \App\Models\AccountCode::all() )  
        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">
            <nav id="sidebar">
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu">
                        <a href="{{ url('/') }}" @if(Request::is('/')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="far fa-home"></i>
                                <span>{{ __('Beranda') }}</span>
                            </div>
                        </a>
                    </li>
                    @if (Auth::check() && !Auth::user()->hasRole('root'))
                        <li class="menu">
                            <a href="#payment" @if(Request::is('payments*')) data-active="true" aria-expanded="true" @else aria-expanded="false" @endif data-toggle="collapse" class="dropdown-toggle">
                                <div>
                                    <i class="far fa-wallet"></i>
                                    <span>{{ __('Pembayaran') }}</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </div>
                            </a>
                            <ul class="submenu list-unstyled collapse @if(Request::is('payments*')) show @endif" id="payment" data-parent="#accordionExample">
                                <li @if(Request::is('payments/salary')) class="active" @endif>
                                    <a href="{{ url('/payments/salary') }}"> {{ __('Gaji') }}</a>
                                </li>
                                <li @if(Request::is('payments/bonus')) class="active" @endif>
                                    <a href="{{ url('/payments/bonus') }}"> {{ __('Bonus') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <li class="menu">
                        <a href="#report" @if(Request::is('report*')) data-active="true" aria-expanded="true" @else aria-expanded="false" @endif data-toggle="collapse" class="dropdown-toggle">
                            <div>
                                <i class="fal fa-clipboard-list"></i>
                                <span>{{ __('Laporan') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse @if(Request::is('reports*')) show @endif" id="report" data-parent="#accordionExample">
                            <li @if(Request::is('reports/topup')) class="active" @endif>
                                <a href="{{ url('/reports/topup') }}" class="capitalize"> {{ __('Topup') }}</a>
                            </li>
                            <li @if(Request::is('reports/salary')) class="active" @endif>
                                <a href="{{ url('/reports/salary') }}" class="capitalize"> {{ __('Gaji') }}</a>
                            </li>
                            <li @if(Request::is('reporsubments/disbursement/bonus')) class="active" @endif>
                                <a href="{{ url('/reports/disbursement/bonus') }}" class="capitalize"> {{ __('Bonus') }}</a>
                            </li>
                            <li @if(Request::is('reports/historyInvoice')) class="active" @endif>
                                <a href="{{ url('/reports/historyInvoice') }}" class="capitalize"> {{ __('Invoice') }}</a>
                            </li>
                            @foreach($accounts as $account)
                            <li @if(Request::is('reports/disbursement/'.$account->slug)) class="active" @endif>
                                <a href="{{ url('/reports/disbursement/'.$account->slug) }}" class="capitalize"> {{ __($account->name) }}</a>
                            </li>
                            @endforeach
                            <li @if(Request::is('reports/export')) class="active" @endif>
                                <a href="{{ url('/reports/export') }}" class="capitalize"> {{ __('Export') }}</a>
                            </li>
                        </ul>
                    </li>
                    @if (Auth::check() && Auth::user()->hasRole('admin'))
                    <li class="menu">
                        <a href="#invoice" @if(Request::is('invoice*')) data-active="true" aria-expanded="true" @else aria-expanded="false" @endif data-toggle="collapse" class="dropdown-toggle">
                            <div>
                                <i class="fal fa-clipboard-list"></i>
                                <span>{{ __('Invoice') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse @if(Request::is('invoices*')) show @endif" id="invoice" data-parent="#accordionExample">
                            <li @if(Request::is('invoices/invoiceContact')) class="active" @endif>
                                <a href="{{ url('/invoices/invoiceContact') }}"> {{ __('Contact') }}</a>
                            </li>
                            <li @if(Request::is('invoices/createInvoice')) class="active" @endif>
                                <a href="{{ url('/invoices/createInvoice') }}"> {{ __('Create Invoice') }}</a>
                            </li>
                        </ul>
                    </li>
                    @foreach($accounts as $account)
                    <li class="menu">
                        <a href="#slug{{ $account->slug }}" @if(Request::is('/'.$account->slug)) data-active="true" aria-expanded="true" @else aria-expanded="false" @endif data-toggle="collapse" class="dropdown-toggle capitalize">
                            <div>
                                <i class="fal fa-clipboard-list"></i>
                                <span>{{ __($account->name) }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse @if(Request::is('/'.$account->slug)) show @endif" id="slug{{ $account->slug }}" data-parent="#accordionExample">
                            <li @if(Request::is($account->slug.'/contact')) class="active" @endif>
                                <a href="{{ url($account->slug.'/contact') }}" class="capitalize"> {{ __('Contact') }}</a>
                            </li>
                            <li @if(Request::is($account->slug)) class="active" @endif>
                                <a href="{{ url($account->slug) }}" class="capitalize"> {{ __('Transaction') }}</a>
                            </li>
                        </ul>
                    </li>
                    @endforeach
                    @endif
                    @if (Auth::check() && Auth::user()->hasRole('root'))
                        <li class="menu">
                            <a href="{{ url('/accounts') }}" @if(Request::is('accounts*')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                                <div>
                                    <i class="fad fa-users"></i>
                                    <span>{{ __('Account') }}</span>
                                </div>
                            </a>
                        </li>
                        <li class="menu">
                            <a href="{{ url('/admins') }}" @if(Request::is('admins*')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                                <div>
                                    <i class="fad fa-users"></i>
                                    <span>{{ __('Admin') }}</span>
                                </div>
                            </a>
                        </li>
                        <li class="menu">
                            <a href="{{ url('/logs') }}" @if(Request::is('logs*')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                                <div>
                                    <i class="far fa-clipboard-list"></i>
                                    <span>{{ __('Logs') }}</span>
                                </div>
                            </a>
                        </li>
                    @endif
                    <li class="menu">
                        <a href="#batches" @if(Request::is('batches*')) data-active="false" aria-expanded="true" @else aria-expanded="false" @endif data-toggle="collapse" class="dropdown-toggle">
                            <div>
                                <i class="fal fa-clipboard-list"></i>
                                <span>{{ __('Transfer') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse @if(Request::is('batches*')) show @endif" id="batches" data-parent="">
                            <li @if(Request::is('batches/contact')) class="active" @endif>
                                <a href="{{ url('/batches/contact') }}"> {{ __('Contact') }}</a>
                            </li>
                            <li @if(Request::is('batches')) class="active" @endif>
                                <a href="{{ url('/batches') }}"> {{ __('Transfer') }}</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu">
                        <a href="#donation" @if(Request::is('donation*')) data-active="false" aria-expanded="true" @else aria-expanded="false" @endif data-toggle="collapse" class="dropdown-toggle">
                            <div>
                                <i class="fal fa-clipboard-list"></i>
                                <span>{{ __('Donasi') }}</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse @if(Request::is('donation*')) show @endif" id="donation" data-parent="">
                            <li @if(Request::is('donation/personResponsible')) class="active" @endif>
                                <a href="{{ url('/donation/personResponsible') }}"> {{ __('Penanggung Jawab') }}</a>
                            </li>
                            <li @if(Request::is('donation')) class="active" @endif>
                                <a href="{{ url('/donation') }}"> {{ __('Donasi') }}</a>
                            </li>
                        </ul>
                    </li>
                    {{-- <li class="menu">
                        <a href="{{ url('/batches') }}" @if(Request::is('batches*')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="fad fa-chevron-right"></i>
                                <span>{{ __('Transaksi') }}</span>
                            </div>
                        </a>
                    </li> --}}
                    <li class="menu">
                        <a href="{{ url('/webhooks') }}" @if(Request::is('webhooks')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="fad fa-chevron-right"></i>
                                <span>{{ __('Webhook') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{ url('/employes') }}" @if(Request::is('employes*')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="fad fa-chevron-right"></i>
                                <span>{{ __('Karyawan') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{ url('/branches') }}" @if(Request::is('branches')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="fad fa-chevron-right"></i>
                                <span>{{ __('Cabang') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{ url('/companies') }}" @if(Request::is('companies')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="fad fa-chevron-right"></i>
                                <span>{{ __('Perusahaan') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{ url('/departments') }}" @if(Request::is('departments')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="fad fa-chevron-right"></i>
                                <span>{{ __('Departemen') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{ url('/positions') }}" @if(Request::is('positions')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="fad fa-chevron-right"></i>
                                <span>{{ __('Jabatan') }}</span>
                            </div>
                        </a>
                    </li>
                    @if (Auth::check() && Auth::user()->hasRole('root'))
                        <li class="menu">
                            <div style="cursor: default;  pointer-events: none;" class="dropdown-toggle">
                                <span>{{ __('Settings') }}</span>
                            </div>
                        </li>
                        <li class="menu">
                            <a href="{{ url('/fee_rules') }}" @if(Request::is('fee_rules*')) data-active="true" @endif aria-expanded="false" class="dropdown-toggle">
                                <div>
                                    <i class="fad fa-users"></i>
                                    <span>{{ __('Fee Rule') }}</span>
                                </div>
                            </a>
                        </li>
                    @endif
                    {{--
                    <li class="menu">
                        <a href="#catalogue" @if(Request::is('catalogue*')) data-active="true" aria-expanded="true" @else aria-expanded="false" @endif data-toggle="collapse" class="dropdown-toggle">
                            <div>
                                <i class="far fa-store"></i>
                                <span>Katalog</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse @if(Request::is('catalogue*')) show @endif" id="catalogue" data-parent="#accordionExample">
                            <li @if(!Request::is('catalogue/create') && Request::is('catalogue*')) class="active" @endif>
                                <a href="{{ url('/catalogue') }}"> List Katalog</a>
                            </li>
                            <li @if(Request::is('catalogue/create')) class="active" @endif>
                                <a href="{{ url('/catalogue/create') }}"> Buat Katalog</a>
                            </li>
                        </ul>
                    </li>
                     --}}
                </ul>
            </nav>
        </div>
        <!--  END SIDEBAR  -->

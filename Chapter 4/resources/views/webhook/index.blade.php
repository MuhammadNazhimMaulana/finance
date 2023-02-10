@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/tables/table-basic.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script type="text/javascript">

        // eventListener
        const changeUrl = document.querySelector("#ubah");

        // If "Ubah" is clicked
        if(changeUrl)
        {
            changeUrl.addEventListener('click', e => {
            e.preventDefault()
                value = document.querySelector("#value")
                saveUrl = document.querySelector("#saveUrl")
                hide = document.querySelector("#hide")
    
                value.removeAttribute("disabled")
                value.removeAttribute("readonly")
       
                // ChangeUrl Class
                hide.classList.add("d-none")
    
                // ChangeUrl Class
                saveUrl.classList.remove("d-none")

            })
        }
    </script>
@endpush
@section('breadcrumb')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<ul class="navbar-nav flex-row">
    <li>
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><span>{{ __('Config') }}</span></li>
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
            <h4 class="mb-3">Webhook</h4>
            <div class="mb-4 mt-4">
                @include('layouts._partials.alert')

                {{-- Tabel --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-dark">
                                <thead class="thead-dark|thead-light">
                                    <tr>
                                        <th scope="col">Pengaturan</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                   {{-- Url Config --}}
                                    <tr>
                                        <td>URL Webhook</td>
                                        @if($data)
                                        <td>
                                            <ul class="table-controls">
                                                <li class="w-50">
                                                    <form method="POST" action="{{ action('Webhook\DonationWebhookController@store') }}">
                                                    @csrf
                                                    {{-- Decide The Value --}}
                                                    <input value="{{ $data }}" name="value" type="text" class="form-control input-md w-75" id="value" disabled="true">
                                                </li>
                                                <li id="hide">
                                                    <button class="btn btn-success btn-block" id="ubah">{{ __('Ubah') }}</button>   
                                                </li>
                                                <li id="saveUrl">
                                                        <button class="btn btn-success btn-block" type="submit">{{ __('Save') }}</button>
                                                    </form>      
                                                </li>
                                            </ul>
                                        </td>
                                        @else       
                                        <td>
                                            <ul class="table-controls">
                                                <li class="w-50">
                                                    <form method="POST" action="{{ action('Webhook\DonationWebhookController@store') }}">
                                                    @csrf
                                                    {{-- Decide The Value --}}
                                                    <input value="{{ $data }}" name="value" type="text" class="form-control input-md w-75" id="value">
                                                </li>
                                                <li>
                                                        <button class="btn btn-success btn-block" type="submit">{{ __('Simpan') }}</button>
                                                    </form>      
                                                </li>
                                            </ul>
                                        </td>
                                        @endif
                                    </tr>

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

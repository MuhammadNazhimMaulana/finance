@if (session('errorMsg'))
    <div class="alert alert-light-danger border-0 mb-4" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fal fa-times"></i></button>
        {{ session('errorMsg') }}
    </div>
@endif
@if (session('successMsg'))
    <div class="alert alert-light-success border-0 mb-4" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fal fa-times"></i></button>
        {{ session('successMsg') }}
    </div>
@endif
@if (session('status'))
    <div class="alert alert-light-success border-0 mb-4" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fal fa-times"></i></button>
        {{ session('status') }}
    </div>
@endif
@if (Auth::check() && !Request::is('profile'))
    @if (Auth::user()->hasRole('admin') && !Auth::user()->pin)
        <div class="alert alert-light-warning border-0 mb-4" role="alert">
            Anda belum mengkonfigurasi PIN, silahkan <a href="{{ url('/profile') }}">Klik Disini</a> untuk mengkonfigurasi PIN Anda.
        </div>
    @endif
@endif
@if ($errors->any())
    <div class="alert alert-light-danger border-0 mb-4" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fal fa-times"></i></button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 25px;
        }

        td,
        th {
            text-align: left;
            font-weight: 400
        }
    </style>

    <title>{{ $title }}</title>
</head>

<body>
    <div style="font-size: 64px; color: '#dddddd'; text-align: center; margin-bottom: 50px;">Transaksi Berhasil</div>
    
    <table cellpadding="5">
        <tr>
            <th>Nomor Transaksi</th>
            <th></th>
            <th>{{ $disbursement->xendit_id }}</th>
        </tr>
        <tr>
            <th>Tanggal Transaksi</th>
            <th></th>
            <th>{{ date_format($disbursement->created_at,'d-m-Y') }}</th>
        </tr>
        <tr>
            <th>Waktu Transaksi</th>
            <th></th>
            <th>{{ date_format($disbursement->created_at,'H:i:s') }}</th>
        </tr>
    </table>
    <hr>
    <table cellpadding="5">
        <tr>
            <th>Rekening Tujuan</th>
            <th></th>
            <th>{{ $disbursement->bank_account_number }}</th>
        </tr>
        <tr>
            <th>Nama Penerima</th>
            <th></th>
            <th>{{ $disbursement->bank_account_holder_name }}</th>
        </tr>
        <tr>
            <th>Email Penerima</th>
            <th></th>
            <th>{{ $disbursement->to_email }}</th>
        </tr>
        <tr>
            <th>Bank Tujuan</th>
            <th></th>
            <th>{{ $disbursement->bank_name }}</th>
        </tr>
    </table>
    <hr>
    <table cellpadding="5">
        <tr>
            <th>Nama Pengirim</th>
            <th></th>
            <th>{{ $sender->name }}</th>
        </tr>
        <tr>
            <th>Nominal</th>
            <th></th>
            <th style="font-weight: 600;">{{ \App\Helpers\CurrencyHelper::toIDR($disbursement->amount) }}</th>
        </tr>
        <tr>
            <th>Biaya Admin</th>
            <th></th>
            <th style="font-weight: 600;">{{ \App\Helpers\CurrencyHelper::toIDR($disbursement->fee) }}</th>
        </tr>
        <tr>
            <th>Total</th>
            <th></th>
            <th style="font-weight: 600;">{{ \App\Helpers\CurrencyHelper::toIDR($disbursement->total) }}</th>
        </tr>
        <tr>
            <th>Berita</th>
            <th></th>
            <th>{{ $disbursement->description }}</th>
        </tr>
    </table>

    <p>
        {{-- Kepada : {{ $pembelian->user->first_name }}<br>
        No. Transaksi : {{ $pembelian->id_pembelian }}<br>
        Tanggal : {{ $pembelian->created_at->toDateString() }} --}}
    </p>

</body>

</html>
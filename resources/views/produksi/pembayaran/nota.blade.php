<!DOCTYPE html>
<html>
<head>
    <title>Nota Pembayaran</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('assets/img/cv-mutiaraberlian-icon.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/nota.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Courgette" rel="stylesheet">
</head>
<body>
<style>
    body {
        -webkit-print-color-adjust: exact !important;
    }
</style>
<div class="btn-print">
    <button type="button" onclick="printPage()">Print</button>
</div>
<div class="div-width page-break">
    <div class="col-6">

        <h1 class="m-unset">Mutiara Berlian</h1>
        <h3>Nota Pembayaran</h3>

    </div>

    <div class="col-6">
        <table class="border-none" width="100%">
            <tr>
                <td>No.</td>
                <td width="1%">:</td>
                <td>{{ $data->po_nota }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td width="1%">:</td>
                <td>{{ $date }}</td>
            </tr>
        </table>
    </div>

    <table width="100%" class="mt-3" cellpadding="5px">
        <tr>
            <td width="30%" style="background-color: #bdc3c7; font-weight: bold">Nama</td>
            <td>{{ $data->s_name }}</td>
        </tr>
        <tr>
            <td width="30%" style="background-color: #bdc3c7; font-weight: bold">Tanggal Pembelian</td>
            <td>{{ $data->po_date }}</td>
        </tr>
        <tr>
            <td width="30%" style="background-color: #bdc3c7; font-weight: bold">Termin ke-</td>
            <td>{{ $data->pop_termin }}</td>
        </tr>
    </table>

    <table width="100%" class="mt-3" cellpadding="5px">
        <tr>
            <td width="30%" style="background-color: #bdc3c7; font-weight: bold">Tagihan</td>
            <td>Rp. {{ Currency::addCurrency($data->pop_value) }}</td>
        </tr>
        <tr>
            <td width="30%" style="background-color: #bdc3c7; font-weight: bold">Nilai Bayar</td>
            <td>Rp. {{ Currency::addCurrency($data->pop_pay) }}</td>
        </tr>
        <tr>
            <td width="30%" style="background-color: #bdc3c7; font-weight: bold">Kekurangan</td>
            <td>Rp. {{ Currency::addCurrency($kekurangan) }}</td>
        </tr>
    </table>
</div>
</body>
</html>
<script src="{{asset('assets/jquery/jquery-3.1.0.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        window.print();
    })

    function printPage() {
        window.print();
    }
</script>


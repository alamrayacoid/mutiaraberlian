<!DOCTYPE html>
<html>
<head>
    <title>Nota Return Order Produksi</title>
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
<div class="btn-print">
    <button type="button" onclick="javascript:window.print();">Print</button>
</div>
<div class="div-width page-break">
    <div class="col-6">

        <h1 class="m-unset">Mutiara Berlian</h1>
        <h3>Nota Return Order Produksi</h3>

    </div>

    <div class="col-6">
        <table class="border-none" width="100%">
            <tr>
                <td>Nota Order Produksi</td>
                <td width="1%">:</td>
                <td>{{ $val['nota_po'] }}</td>
            </tr>
            <tr>
                <td>Nota Return</td>
                <td width="1%">:</td>
                <td>{{ $val['nota'] }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td width="1%">:</td>
                <td>{{ $val['tanggal'] }}</td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td width="1%">:</td>
                <td>{{ $val['supplier'] }}</td>
            </tr>
        </table>
    </div>

    <table width="100%" class="mt-3" cellpadding="5px">
        <thead>
            <tr>
                <th width="40%">Barang</th>
                <th width="10%">Qty</th>
                <th width="15%">Metode</th>
                <th width="35%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $val['barang'] }}</td>
                <td>{{ $val['qty'] }}</td>
                <td>{{ $val['metode'] }}</td>
                <td>{{ $val['keterangan'] }}</td>
            </tr>
        </tbody>
    </table>

    <table width="100%" class="mt-3" cellpadding="5px">
        <thead>
        <tr>
            <th width="70%">Kode</th>
            <th width="30%">Qty</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $val['kode'] }}</td>
            <td>{{ $val['qtykode'] }}</td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
<script src="{{asset('assets/jquery/jquery-3.1.0.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        window.print();
    })
</script>

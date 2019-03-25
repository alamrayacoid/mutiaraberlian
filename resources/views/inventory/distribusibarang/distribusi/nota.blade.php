<!DOCTYPE html>
<html>
<head>
	<title>Nota Distribusi Barang</title>
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
				<h3>Nota Distribusi Barang</h3>

			</div>

			<div class="col-6">
				<table class="border-none" width="100%">
					<tr>
						<td width="20%">No. Nota</td>
						<td width="1%">:</td>
						<td>{{$data->sd_nota}}</td>
					</tr>
					<tr>
						<td>Tanggal</td>
						<td width="5%">:</td>
						<td>{{Carbon\Carbon::parse($data->sd_date)->format('d/m/Y')}}</td>
					</tr>
					<tr>
						<td>Cabang Asal</td>
						<td width="5%">:</td>
						<td>{{$cabang->c_name}}</td>
					</tr>
					<tr>
						<td>Cabang Tujuan</td>
						<td width="5%">:</td>
						<td>{{$tujuan->c_name}}</td>
					</tr>
				</table>
			</div>

			<table width="100%" class="mt-3" cellpadding="5px">
				<thead>
					<tr>
						<th width="1%">No</th>
						<th width="40%">Nama - Kode Barang</th>
						<th>Satuan</th>
						<th>Jumlah</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($dt as $key => $value)
						<tr>
							<td align="center">{{$key + 1}}</td>
							<td align="center">{{$value->i_code}} - {{$value->i_name}}</td>
							<td align="center">{{$value->u_name}}</td>
							<td align="center">{{$value->sdd_qty}}</td>
						</tr>
					@endforeach
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

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
			<h1 class="m-unset">Mutiara Berlian</h1>
			<h3>Nota Distribusi Barang</h3>
			<!-- <div class="col-md-6 col-sm-6">
			</div> -->
			<hr>

			<div class="col-md-6 col-sm-6">
				<table class="border-none" width="100%">
					<tr>
						<td width="20%">No. Nota</td>
						<td width="1%">:</td>
						<td>{{$data->sd_nota}}</td>

						<td width="10%"></td>

						<td>Cabang Asal</td>
						<td width="5%">:</td>
						<td>{{$cabang->c_name}}</td>
					</tr>
					<tr>
						<td>Tanggal</td>
						<td width="5%">:</td>
						<td>{{Carbon\Carbon::parse($data->sd_date)->format('d/m/Y')}}</td>

						<td width="10%"></td>

						<td>Cabang Tujuan</td>
						<td width="5%">:</td>
						<td>{{$tujuan->c_name}}</td>
					</tr>
					<tr>
						<td width="20%">Ekspedisi yang digunakan</td>
						<td width="1%">:</td>
						<td>{{ $ekspedisi->getExpedition->e_name }}</td>

						<td width="10%"></td>

						<td>No. Resi</td>
						<td width="5%">:</td>
						<td>{{ $ekspedisi->pd_resi }}</td>
					</tr>
					<tr>
						<td width="20%">Jenis Ekspedisi</td>
						<td width="1%">:</td>
						<td>{{ $ekspedisi->getExpeditionType->ed_product }}</td>

						<td width="10%"></td>

						<td>Kurir</td>
						<td width="5%">:</td>
						<td>{{ $ekspedisi->pd_couriername }} ( {{ $ekspedisi->pd_couriertelp }} )</td>
					</tr>
				</table>
			</div>
			<br>

			<table width="100%" class="mt-3" cellpadding="5px">
				<thead>
					<tr>
						<th width="1%">No</th>
						<th width="40%">Kode Barang - Nama</th>
						<th>Satuan</th>
						<th>Jumlah</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($dt as $key => $value)
						<tr>
							<td align="center">{{$key + 1}}</td>
							<td class="text-left">{{$value->i_code}} - {{$value->i_name}}</td>
							<td class="text-left">{{$value->u_name}}</td>
							<td class="text-right digits">{{$value->sdd_qty}}</td>
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

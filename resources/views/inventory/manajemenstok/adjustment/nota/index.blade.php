<!DOCTYPE html>
<html>
<head>
	<title>Nota Opname</title>
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
				<h3>Nota Opname</h3>

			</div>

			<div class="col-6">
				<table class="border-none" width="100%">
					<tr>
						<td width="20%">No. Nota</td>
						<td width="1%">:</td>
						<td>{{$data->o_nota}}</td>
					</tr>
					<tr>
						<td>Tanggal</td>
						<td width="5%">:</td>
						<td>{{Carbon\Carbon::parse($data->o_date)}}</td>
					</tr>
				</table>
			</div>

			<table width="100%" class="mt-3" cellpadding="5px">
				<thead>
					<tr>
						<th width="1%">No</th>
						<th width="40%">Nama Barang</th>
						<th>Qty Real</th>
						<th>Unit Real</th>
            <th>Qty System</th>
            <th>Unit System</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center">1</td>
						<td align="center">{{$data->i_name}}</td>
						<td align="center">{{(int)$data->o_qtyreal}}</td>
						<td align="center">{{$unitsistem->u_name}}</td>
						<td align="center">{{(int)$data->o_qtysystem}}</td>
						<td align="center">{{$unitreal->u_name}}</td>
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

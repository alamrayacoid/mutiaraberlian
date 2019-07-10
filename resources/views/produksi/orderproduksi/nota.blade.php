<!DOCTYPE html>
<html>
<head>
	<title>Nota Order Produksi</title>
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
				<h3>Nota Order Produksi</h3>
				
			</div>
			
			<div class="col-6">
				<table class="border-none" width="100%">
					<tr>
						<td>No.</td>
						<td width="1%">:</td>
						<td>{{ $header->nota }}</td>
					</tr>
					<tr>
						<td>Tanggal</td>
						<td width="1%">:</td>
						<td>{{date('d-m-Y', strtotime($header->tanggal))}}</td>
					</tr>
					<tr>
						<td>Suplier</td>
						<td width="1%">:</td>
						<td>{{ $header->supplier }}</td>
					</tr>
				</table>
			</div>
			
			<table width="100%" class="mt-3" cellpadding="5px">
				<thead>
					<tr>
						<th width="1%">No</th>
						<th width="40%">Barang</th>
						<th width="1%">Qty</th>
						<th width="15%">Satuan</th>
						<th>Harga</th>
						<th>Sub Total</th>
					</tr>
				</thead>
				<tbody>
                    @foreach($item as $key => $dtItem)
					<tr>
						<td align="center">{{ $key+1 }}</td>
						<td>{{ $dtItem->barang }}</td>
						<td align="center">{{ $dtItem->qty }}</td>
						<td>{{ $dtItem->satuan }}</td>
						<td>
							<div class="w-100">
								<div class="float-left">Rp. </div><div class="float-right">{{ Currency::addCurrency($dtItem->value) }}</div>
							</div>
						</td>
						<td>
							<div class="w-100">
								<div class="float-left">Rp. </div><div class="float-right">{{ Currency::addCurrency($dtItem->totalnet) }}</div>
                                <input type="hidden" class="totalnet" value="{{ number_format($dtItem->totalnet,0,'','') }}">
							</div>
						</td>
					</tr>
                    @endforeach
				</tbody>
				<tfoot>
					<tr>
						<td class="tebal" align="right" colspan="5">Total Net</td>
						<td>
							<div class="float-left">Rp. </div><div class="float-right" id="totalnet"></div>
						</td>
					</tr>
				</tfoot>
			</table>
			<h1 class="text-left">Termin Pembayaran</h1>
			<table width="100%" cellpadding="5px">
				<thead>
					<tr>
						<th width="1%">No</th>
						<th>Estimasi</th>
						<th>Nominal</th>
					</tr>
				</thead>
				<tbody>
                @foreach($termin as $key => $dtTermin)
					<tr>
						<td align="center">{{ $dtTermin->termin }}</td>
						<td align="center">{{ date('d-m-Y', strtotime($dtTermin->tanggal)) }}</td>
						<td>
							<div class="float-left">Rp. </div><div class="float-right">{{ Currency::addCurrency($dtTermin->value) }}</div>
                            <input type="hidden" class="totaltermin" value="{{ number_format($dtTermin->value,0,'','') }}">
						</td>
					</tr>
                @endforeach
				</tbody>
                <tfoot>
                    <tr>
                        <td class="tebal" align="right" colspan="2">Total Termin</td>
                        <td>
                            <div class="float-left">Rp. </div><div class="float-right" id="totaltermin"></div>
                        </td>
                    </tr>
                </tfoot>
			</table>
		</div>
</body>
</html>
<script src="{{asset('assets/jquery/jquery-3.1.0.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        hitungTotalNet();
        hitungTotalTermin();
        window.print();
    })

    function convertToCurrency(angka) {
        var currency = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) currency += angkarev.substr(i,3)+'.';
        var hasil = currency.split('',currency.length-1).reverse().join('');
        return hasil;

    }

    function hitungTotalNet() {
        var inpTotNet = document.getElementsByClassName( 'totalnet' ),
            totNet  = [].map.call(inpTotNet, function( input ) {
                return parseInt(input.value);
            });

        var total = 0;
        for (var i =0; i < totNet.length; i++) {
            total += parseInt(totNet[i]);
        }

        $("#totalnet").html(convertToCurrency(total));
    }

    function hitungTotalTermin() {
        var inpTotTermin = document.getElementsByClassName( 'totaltermin' ),
            totTermin  = [].map.call(inpTotTermin, function( input ) {
                return parseInt(input.value);
            });

        var total = 0;
        for (var i =0; i < totTermin.length; i++) {
            total += parseInt(totTermin[i]);
        }

        $("#totaltermin").html(convertToCurrency(total));
    }
</script>

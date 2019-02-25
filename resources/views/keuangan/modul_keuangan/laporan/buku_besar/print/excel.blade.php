<?php 
	$tanggal_1 = switchBulan(explode('/', $_GET['d1'])[0]).' '.explode('/', $_GET['d1'])[1];

	$tanggal_2 = switchBulan(explode('/', $_GET['d2'])[0]).' '.explode('/', $_GET['d2'])[1];

	$type = "Semua Akun";

	if(!isset($_GET['semua']))
		$type = $_GET['akun1']." s/d ".$_GET['akun2'];
?>

<table width="100%">
	<thead>
		<tr>
			<td></td>
			<td colspan="8"></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="8">Laporan Buku Besar <small> ({{ $type }})</small></td>
		</tr>

		<tr>
			<td></td>
			<td colspan="8">{{ jurnal()->companyName }}</td>
		</tr>

		<tr>
			<td></td>
			<td colspan="8" style="border-bottom: 1px solid #ccc; padding-bottom: 20px;"><small>{{ $tanggal_1 }}&nbsp; s/d&nbsp; {{ $tanggal_2 }}</small></td>
		</tr>
	</thead>
</table>

<br>

@foreach($data["data"] as $key => $akun)
	
	<?php 
		$margin =  ($key > 0) ? 'margin-top: 40px;' : '';
	?>

	<table width="100%" style="font-size: 9.5pt; {{ $margin  }}">
		<tbody>
			<tr>
				<td></td>
				<td colspan="8" style="background-color: #0099CC; color: #ffffff;"> {{ $akun->ak_id.' - '.$akun->ak_nama }} </td>
			</tr>

			<tr>
				<td></td>
				<td style="text-align: center; color: #ffffff; background-color: #555555"> Tanggal </td>
				<td style="text-align: center; color: #ffffff; background-color: #555555"> No.Bukti </td>
				<td style="text-align: center; color: #ffffff; background-color: #555555"> Keterangan </td>
				<td style="text-align: center; color: #ffffff; background-color: #555555"> DK </td>
				<td style="text-align: center; color: #ffffff; background-color: #555555"> Kode Akun </td>
				<td style="text-align: center; color: #ffffff; background-color: #555555"> Debet </td>
				<td style="text-align: center; color: #ffffff; background-color: #555555"> Kredit </td>
				<td style="text-align: center; color: #ffffff; background-color: #555555"> Saldo </td>
			</tr>

			<?php 
				$dk = "";

				if($akun->ak_saldo_awal < 0){
					if($akun->ak_posisi == "D")
						$dk = "K";
					else
						$dk = "D";
				}else{
					$dk = $akun->ak_posisi;
				}

				$saldo = $akun->ak_saldo_awal;
			?>

			<tr>
				<td></td>
				<td style="text-align: center; padding: 2px 5px;"> - </td>
				<td style="text-align: center; padding: 2px 5px;"> - </td>
				<td style="padding: 2px 5px;"> Saldo Awal {{ $tanggal_1 }} </td>
				<td style="text-align: center; padding: 2px 5px;"> {{ $dk }} </td>
				<td style="text-align: center; padding: 2px 5px;"> {{ $akun->ak_id }} </td>
				<td style="text-align: right; padding: 2px 5px;"> {{ ($dk == "D") ? $akun->ak_saldo_awal : number_format(0, 2) }} </td>
				<td style="text-align: right; padding: 2px 5px;"> {{ ($dk == "K") ? $akun->ak_saldo_awal : number_format(0, 2) }} </td>
				<td style="text-align: right; padding: 2px 5px;"> 
					{{ $akun->ak_saldo_awal }}
				</td>
			</tr>

			@if(isset($_GET['lawan']) == 'true')
				<tr>
					<td></td>
					<td colspan="8" style="background-color: #eeeeee;">&nbsp;</td>
				</tr>
			@endif

			@if(count($akun->jurnal_detail))

				<?php 
					if($akun->jurnal_detail[0]->jrdt_dk != $akun->ak_posisi)
						$saldo -= $akun->jurnal_detail[0]->jrdt_value;
					else
						$saldo += $akun->jurnal_detail[0]->jrdt_value;
				?>

				<tr>
					<td></td>
					<td style="text-align: center; padding: 2px 5px;"> {{ $akun->jurnal_detail[0]['jurnal']->jr_tanggal_trans }} </td>
					<td style="text-align: center; padding: 2px 5px; font-size: 8pt;"> {{  $akun->jurnal_detail[0]['jurnal']->jr_ref }} </td>
					<td style="padding: 2px 5px;"> {{  $akun->jurnal_detail[0]['jurnal']->jr_keterangan }} </td>
					<td style="text-align: center; padding: 2px 5px;"> {{  $akun->jurnal_detail[0]->jrdt_dk }} </td>
					<td style="text-align: center; padding: 2px 5px;"> {{ $akun->jurnal_detail[0]->jrdt_akun }} </td>
					<td style="text-align: right; padding: 2px 5px;"> {{ ($akun->jurnal_detail[0]->jrdt_dk == "D") ? $akun->jurnal_detail[0]->jrdt_value : number_format(0, 2) }} </td>
					<td style="text-align: right; padding: 2px 5px;"> {{ ($akun->jurnal_detail[0]->jrdt_dk == "K") ? $akun->jurnal_detail[0]->jrdt_value : number_format(0, 2) }} </td>
					<td style="text-align: right; padding: 2px 5px;"> 
						{{ $saldo }}
					</td>
				</tr>

				@if(isset($_GET['lawan']) == 'true')
					@foreach($akun->jurnal_detail[0]->jurnal->detail as $idx => $detail)

						@if($detail->jrdt_akun != $akun->jurnal_detail[0]->jrdt_akun)

							<tr>
								<td></td>
								<td style="text-align: center; padding: 2px 5px;"> {{ $akun->jurnal_detail[0]['jurnal']->jr_tanggal_trans }} </td>
								<td style="text-align: center; padding: 2px 5px; font-size: 8pt;"> {{  $akun->jurnal_detail[0]['jurnal']->jr_ref }} </td>
								<td style="padding: 2px 5px;"> {{  $akun->jurnal_detail[0]['jurnal']->jr_keterangan }} </td>
								<td style="text-align: center; padding: 2px 5px;"> {{  $detail->jrdt_dk }} </td>
								<td style="text-align: center; padding: 2px 5px;"> {{ $detail->jrdt_akun }} </td>
								<td style="text-align: right; padding: 2px 5px;"> {{ ($detail->jrdt_dk == "D") ? $detail->jrdt_value : number_format(0, 2) }} </td>
								<td style="text-align: right; padding: 2px 5px;"> {{ ($detail->jrdt_dk == "K") ? $detail->jrdt_value : number_format(0, 2) }} </td>
								<td style="text-align: right; padding: 2px 5px;"> 
									
								</td>
							</tr>
						@endif

					@endforeach

					<tr>
						<td></td>
						<td colspan="8" style="background-color: #eeeeee;">&nbsp;</td>
					</tr>

					<tr>
						<td></td>
						<td colspan="8" style="background-color: none;">&nbsp;</td>
					</tr>

				@endif

			@endif

		</tbody>
	</table>

@endforeach
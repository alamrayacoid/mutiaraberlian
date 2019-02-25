<style type="text/css" media="print">
    @page { size: landscape; }
</style>

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
			<td style="font-weight: 800">Laporan Buku Besar <small>({{ $type }})</small></td>
		</tr>

		<tr>
			<td>{{ jurnal()->companyName }}</td>
		</tr>

		<tr>
			<td style="border-bottom: 1px solid #ccc; padding-bottom: 20px;"><small>{{ $tanggal_1 }}&nbsp; s/d&nbsp; {{ $tanggal_2 }}</small></td>
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
				<td class="head" colspan="8" style="background-color: #0099CC; color: white; padding: 10px;"> {{ $akun->ak_id.' - '.$akun->ak_nama }} </td>
			</tr>

			<tr>
				<td width="8%" style="text-align: center; border: 1px solid #0099CC; padding: 5px;"> Tanggal </td>
				<td width="13%" style="text-align: center; border: 1px solid #0099CC; padding: 5px;"> No.Bukti </td>
				<td width="31%" style="text-align: center; border: 1px solid #0099CC; padding: 5px;"> Keterangan </td>
				<td width="5%" style="text-align: center; border: 1px solid #0099CC; padding: 5px;"> DK </td>
				<td width="7%" style="text-align: center; border: 1px solid #0099CC; padding: 5px;"> Kode Akun </td>
				<td width="12%" style="text-align: center; border: 1px solid #0099CC; padding: 5px;"> Debet </td>
				<td width="12%" style="text-align: center; border: 1px solid #0099CC; padding: 5px;"> Kredit </td>
				<td width="12%" style="text-align: center; border: 1px solid #0099CC; padding: 5px;"> Saldo </td>
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
				<td style="text-align: center; padding: 2px 5px;"> - </td>
				<td style="text-align: center; padding: 2px 5px;"> - </td>
				<td style="padding: 2px 5px;"> Saldo Awal {{ $tanggal_1 }} </td>
				<td style="text-align: center; padding: 2px 5px;"> {{ $dk }} </td>
				<td style="text-align: center; padding: 2px 5px;"> {{ $akun->ak_id }} </td>
				<td style="text-align: right; padding: 2px 5px;"> {{ ($dk == "D") ? number_format($akun->ak_saldo_awal, 2) : number_format(0, 2) }} </td>
				<td style="text-align: right; padding: 2px 5px;"> {{ ($dk == "K") ? number_format($akun->ak_saldo_awal, 2) : number_format(0, 2) }} </td>
				<td style="text-align: right; padding: 2px 5px;"> 
					{{ ($akun->ak_saldo_awal < 0) ? '('.number_format($akun->ak_saldo_awal, 2).')' : number_format($akun->ak_saldo_awal, 2) }}
				</td>
			</tr>

			@if(isset($_GET['lawan']) == 'true')
				<tr>
					<td colspan="8" style="background-color: #eee;">&nbsp;</td>
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
					<td style="text-align: center; padding: 2px 5px;"> {{ $akun->jurnal_detail[0]['jurnal']->jr_tanggal_trans }} </td>
					<td style="text-align: center; padding: 2px 5px; font-size: 8pt;"> {{  $akun->jurnal_detail[0]['jurnal']->jr_ref }} </td>
					<td style="padding: 2px 5px;"> {{  $akun->jurnal_detail[0]['jurnal']->jr_keterangan }} </td>
					<td style="text-align: center; padding: 2px 5px;"> {{  $akun->jurnal_detail[0]->jrdt_dk }} </td>
					<td style="text-align: center; padding: 2px 5px;"> {{ $akun->jurnal_detail[0]->jrdt_akun }} </td>
					<td style="text-align: right; padding: 2px 5px;"> {{ ($akun->jurnal_detail[0]->jrdt_dk == "D") ? number_format($akun->jurnal_detail[0]->jrdt_value, 2) : number_format(0, 2) }} </td>
					<td style="text-align: right; padding: 2px 5px;"> {{ ($akun->jurnal_detail[0]->jrdt_dk == "K") ? number_format($akun->jurnal_detail[0]->jrdt_value, 2) : number_format(0, 2) }} </td>
					<td style="text-align: right; padding: 2px 5px;"> 
						{{ ($saldo < 0) ? '('.number_format($saldo, 2).')' : number_format($saldo, 2) }}
					</td>
				</tr>

				@if(isset($_GET['lawan']) == 'true')
					@foreach($akun->jurnal_detail[0]->jurnal->detail as $idx => $detail)

						@if($detail->jrdt_akun != $akun->jurnal_detail[0]->jrdt_akun)

							<tr>
								<td style="text-align: center; padding: 2px 5px;"> {{ $akun->jurnal_detail[0]['jurnal']->jr_tanggal_trans }} </td>
								<td style="text-align: center; padding: 2px 5px; font-size: 8pt;"> {{  $akun->jurnal_detail[0]['jurnal']->jr_ref }} </td>
								<td style="padding: 2px 5px;"> {{  $akun->jurnal_detail[0]['jurnal']->jr_keterangan }} </td>
								<td style="text-align: center; padding: 2px 5px;"> {{  $detail->jrdt_dk }} </td>
								<td style="text-align: center; padding: 2px 5px;"> {{ $detail->jrdt_akun }} </td>
								<td style="text-align: right; padding: 2px 5px;"> {{ ($detail->jrdt_dk == "D") ? number_format($detail->jrdt_value, 2) : number_format(0, 2) }} </td>
								<td style="text-align: right; padding: 2px 5px;"> {{ ($detail->jrdt_dk == "K") ? number_format($detail->jrdt_value, 2) : number_format(0, 2) }} </td>
								<td style="text-align: right; padding: 2px 5px;"> 
									
								</td>
							</tr>
						@endif

					@endforeach

					<tr>
						<td colspan="8" style="background-color: #eee;">&nbsp;</td>
					</tr>

				@endif

			@endif

		</tbody>
	</table>


@endforeach

<script type="text/javascript">
	window.print()
</script>
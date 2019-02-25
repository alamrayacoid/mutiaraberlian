<style type="text/css" media="print">
    @page { size: portrait; }
</style>

<?php 
	$tanggal_1 = switchBulan(explode('/', $_GET['d1'])[0]).' '.explode('/', $_GET['d1'])[1];
?>

<table width="100%">
	<thead>
		<tr>
			<td style="font-weight: 800">Laporan Arus Kas</td>
		</tr>

		<tr>
			<td>{{ jurnal()->companyName }}</td>
		</tr>

		<tr>
			<td style="border-bottom: 1px solid #ccc; padding-bottom: 20px;"><small>Bulan {{ $tanggal_1 }}</small></td>
		</tr>
	</thead>
</table>

<br>
		<table id="table-data" width="100%" style="font-size: 9pt;">
			<tbody>
				<tr>
					<td style="padding-left: 10px; font-weight: bold">Arus Kas Dari Kegiatan Operasional</td>
					<td></td>
				</tr>
				<?php $ocf = $icf = $fcf = 0; ?>
				@foreach($data['data'] as $key => $level2)
					@if($level2->hld_cashflow == "OCF")

						<?php 
							$totLevel2 = 0;

							foreach($level2->akun as $key => $akun){
								$totLevel2 += $akun->saldo_akhir;
							}

							$ocf += $totLevel2;
						?>
						<tr>
							<td style="vertical-align: top; padding-left: 40px;">
								{{ $level2->hld_id }} - {{ $level2->hld_nama }}
							</td>

							<td style="text-align: right; padding-right: 20px;">
								{{ ($totLevel2 < 0) ? '('.number_format(str_replace('-', '', $totLevel2), 2).')' : number_format($totLevel2, 2) }}
							</td>
						</tr>
					@endif
				@endforeach
				<tr>
					<td style="padding-left: 10px; font-weight: bold; color: #0099CC;">Total Arus Kas Dari Kegiatan Operasional</td>
					<td style="border-top: 1px solid #ccc; padding-right: 20px; text-align: right; font-weight: bold; color: #0099CC;">{{ ($ocf < 0) ? '('.number_format(str_replace('-', '', $ocf), 2).')' : number_format($ocf, 2) }}</td>
				</tr>

				<tr><td colspan="2">&nbsp;</td></tr>

				<tr>
					<td style="padding-left: 10px; font-weight: bold">Arus Kas Dari Kegiatan Pendanaan</td>
					<td></td>
				</tr>
				@foreach($data['data'] as $key => $level2)
					@if($level2->hld_cashflow == "FCF")

						<?php 
							$totLevel2 = 0;

							foreach($level2->akun as $key => $akun){
								$totLevel2 += $akun->saldo_akhir;
							}

							$fcf += $totLevel2;
						?>
						<tr>
							<td style="vertical-align: top; padding-left: 40px;">
								{{ $level2->hld_id }} - {{ $level2->hld_nama }}
							</td>

							<td style="text-align: right; padding-right: 20px;">
								{{ ($totLevel2 < 0) ? '('.number_format(str_replace('-', '', $totLevel2), 2).')' : number_format($totLevel2, 2) }}
							</td>
						</tr>
					@endif
				@endforeach
				<tr>
					<td style="padding-left: 10px; font-weight: bold; color: #0099CC;">Total Arus Kas Dari Kegiatan Pendanaan</td>
					<td style="border-top: 1px solid #ccc; padding-right: 20px; text-align: right; font-weight: bold; color: #0099CC;">{{ ($fcf < 0) ? '('.number_format(str_replace('-', '', $fcf), 2).')' : number_format($fcf, 2) }}</td>
				</tr>

				<tr><td colspan="2">&nbsp;</td></tr>

				<tr>
					<td style="padding-left: 10px; font-weight: bold">Arus Kas Dari Kegiatan Investasi</td>
					<td></td>
				</tr>
				@foreach($data['data'] as $key => $level2)
					@if($level2->hld_cashflow == "ICF")

						<?php 
							$totLevel2 = 0;

							foreach($level2->akun as $key => $akun){
								$totLevel2 += $akun->saldo_akhir;
							}

							$icf += $totLevel2;
						?>
						<tr>
							<td style="vertical-align: top; padding-left: 40px;">
								{{ $level2->hld_id }} - {{ $level2->hld_nama }}
							</td>

							<td style="text-align: right; padding-right: 20px;">
								{{ ($totLevel2 < 0) ? '('.number_format(str_replace('-', '', $totLevel2), 2).')' : number_format($totLevel2, 2) }}
							</td>
						</tr>
					@endif
				@endforeach
				<tr>
					<td style="padding-left: 10px; font-weight: bold; color: #0099CC;">Total Arus Kas Dari Kegiatan Investasi</td>
					<td style="border-top: 1px solid #ccc; padding-right: 20px; text-align: right; font-weight: bold; color: #0099CC;">{{ ($icf < 0) ? '('.number_format(str_replace('-', '', $icf), 2).')' : number_format($icf, 2) }}</td>
				</tr>

				<tr><td colspan="2">&nbsp;</td></tr>
				<tr><td colspan="2">&nbsp;</td></tr>

				<tr>
					<td style="padding: 10px; border-top: 1px solid #ccc; font-weight: bold; color: #0099CC;">Saldo Awal Kas Pada Periode {{ $tanggal_1 }}</td>
					<td style="border-top: 1px solid #ccc; padding-right: 20px; text-align: right; font-weight: bold; color: #0099CC;">{{ ($data['saldo_awal'] < 0) ? '('.number_format(str_replace('-', '', $data['saldo_awal']), 2).')' : number_format($data['saldo_awal'], 2) }}</td>
				</tr>

				<tr>
					<td style="padding: 10px; border-top: 1px solid #ccc; font-weight: bold; color: #0099CC;">Total Arus Kas Dari Semua Aktivitas</td>
					<td style="border-top: 1px solid #ccc; padding-right: 20px; text-align: right; font-weight: bold; color: #0099CC;">{{ (($ocf + $icf + $fcf) < 0) ? '('.number_format(str_replace('-', '', ($ocf + $icf + $fcf)), 2).')' : number_format(($ocf + $icf + $fcf), 2) }}</td>
				</tr>

				<tr>
					<td style="padding: 10px; border-top: 1px solid #ccc; font-weight: bold; color: #0099CC;">Saldo Akhir Kas Seharusnya (Periode {{ $tanggal_1 }})</td>
					<td style="border-top: 1px solid #ccc; padding-right: 20px; text-align: right; font-weight: bold; color: #0099CC;">{{ ($data['saldo_awal'] + ($ocf + $icf + $fcf) < 0) ? '('.number_format(str_replace('-', '', $data['saldo_awal'] + ($ocf + $icf + $fcf)), 2).')' : number_format($data['saldo_awal'] + ($ocf + $icf + $fcf), 2) }}</td>
				</tr>
			</tbody>
		</table>

<script type="text/javascript">
	window.print()
</script>
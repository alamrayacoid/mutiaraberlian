<style type="text/css" media="print">
    @page { size: portrait; }
</style>

<?php 
	$tanggal_1 = explode('/', $_GET['d1'])[0];
?>

<table width="100%">
	<thead>
		<tr>
			<td style="font-weight: 800">Analisa Net Profit Terhadap Ocf</td>
		</tr>

		<tr>
			<td>{{ jurnal()->companyName }}</td>
		</tr>

		<tr>
			<td style="border-bottom: 1px solid #ccc; padding-bottom: 20px;"><small>Periode Tahun {{ $tanggal_1 }}</small></td>
		</tr>
	</thead>
</table>

<br>
		<table class="table" id="table-data" width="100%">
			<thead>
				<tr>
					<th width="20%" style="padding: 5px; border: 1px solid #eee; text-align: center;">Periode</th>
					<th width="30%" style="padding: 5px; border: 1px solid #eee; text-align: center;">Nilai OCF</th>
					<th width="20%" style="padding: 5px; border: 1px solid #eee; text-align: center;">Nilai Net Profit</th>
					<th style="padding: 5px; border: 1px solid #eee; text-align: center;">Persentase Ocf/Net Profit</th>
				</tr>
			</thead>
				@foreach($content['data'] as $key => $sdf)
					<tr>
						<td class="text-center" style="font-weight: 600; text-align: center; border: 1px solid #eee; padding: 5px;">
							{{ date('m/Y', strtotime($sdf['tanggal'])) }}
						</td>

						<td class="text-right" style="text-align: right; border: 1px solid #eee; padding: 5px;">
							{{ number_format($sdf['ocf'], 2) }}
						</td>

						<td class="text-right" style="text-align: right; border: 1px solid #eee; padding: 5px;">
							{{ number_format($sdf['net_profit'], 2) }}
						</td>

						<?php 
							$persen = ($sdf['ocf'] != 0) ? (($sdf['ocf'] / $sdf['net_profit']) * 100) : 0;
						?>

						<td class="text-center" v-if="dta.ocf == 0" style="font-weight: bold; color: #0099CC; text-align: center; border: 1px solid #eee; padding: 5px;">
							{{ number_format($persen, 2) }} %
						</td>
					</tr>
				@endforeach
			<tbody>
				
			</tbody>
		</table>
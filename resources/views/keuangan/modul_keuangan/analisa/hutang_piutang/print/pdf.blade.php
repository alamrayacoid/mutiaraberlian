<style type="text/css" media="print">
    @page { size: portrait; }
</style>

<?php 
	$tanggal_1 = explode('/', $_GET['d1'])[0];
?>

<table width="100%">
	<thead>
		<tr>
			<td style="font-weight: 800">Analisa Hutang Piutang</td>
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
					<th width="20%" rowspan="2" style="vertical-align: middle;text-align: center;border: 1px solid #eee;">Periode</th>
					<th width="40%" colspan="2" style="text-align: center;border: 1px solid #eee;">Piutang</th>
					<th width="40%" colspan="2" style="text-align: center;border: 1px solid #eee;">Hutang</th>
				</tr>

				<tr>
					<td style="text-align: center; background-color: #0099CC; color: #ffffff;">Piutang Baru</td>
					<td style="text-align: center; background-color: #0099CC; color: #ffffff;">Sudah Dibayar</td>


					<td style="text-align: center; background-color: #0099CC; color: #ffffff;">Hutang Baru</td>
					<td style="text-align: center; background-color: #0099CC; color: #ffffff;">Sudah Dibayar</td>
				</tr>
			</thead>
				
			<tbody>
				@foreach($content['data'] as $key => $sdf)
					<tr>
						<td class="text-center" style="font-weight: 600; text-align: center; border: 1px solid #eee; padding: 5px; border: 1px solid #eee;">
							{{ date('m/Y', strtotime($sdf['tanggal'])) }}
						</td>

						<td style="font-weight: 600; text-align: right; border: 1px solid #eee;">
							{{ number_format($sdf['piutang']->total_tagihan, 0) }}
						</td>

						<td style="font-weight: 600; text-align: right; border: 1px solid #eee;">
							{{ number_format($sdf['piutang']->sudah_dibayar, 0) }}
						</td>

						<td style="font-weight: 600; text-align: right; border: 1px solid #eee;">
							{{ number_format($sdf['hutang']->total_tagihan, 0) }}
						</td>

						<td style="font-weight: 600; text-align: right; border: 1px solid #eee;">
							{{ number_format($sdf['hutang']->sudah_dibayar, 0) }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
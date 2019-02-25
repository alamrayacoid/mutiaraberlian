<style type="text/css" media="print">
    @page { size: portrait; }
</style>

<?php 
	$tanggal_1 = explode('/', $_GET['d1'])[0];
?>

<table width="100%">
	<thead>
		<tr>
			<td style="font-weight: 800">Analisa Pertumbuhan Aset</td>
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
					<th width="20%" style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white; padding: 5px;">Periode</th>
					<th width="20%" style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white; padding: 5px;">Nilai Di Awal Periode</th>
					<th width="20%" style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white; padding: 5px;">Penambahan Aset</th>
					<th width="20%" style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white; padding: 5px;">Pengurangan Aset</th>
					<th style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white; padding: 5px;">Nilai Akhir Aset</th>
				</tr>
			</thead>
				
			<tbody>
				@foreach($content['data'] as $key => $sdf)
					<tr>
						<td style="font-weight: 600; text-align: center; border: 1px solid #eee; padding: 5px;">
							{{ date('m/Y', strtotime($sdf['tanggal'])) }}
						</td>

						<td style="font-weight: 600; text-align: right;">
							{{ number_format($sdf['aset']->saldo_awal, 2) }}
						</td>

						<td style="font-weight: 600; text-align: right;">
							{{ number_format($sdf['aset']->penambahan, 2) }}
						</td>

						<td style="font-weight: 600; text-align: right;">
							{{ number_format($sdf['aset']->pengurangan, 2) }}
						</td>

						<td style="font-weight: 600; text-align: right;">
							{{ number_format(($sdf['aset']->saldo_awal + $sdf['aset']->penambahan) - $sdf['aset']->pengurangan, 2) }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
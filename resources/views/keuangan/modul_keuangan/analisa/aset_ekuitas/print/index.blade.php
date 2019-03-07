<style type="text/css" media="print">
    @page { size: portrait; }
</style>

<?php 
	$tanggal_1 = explode('/', $_GET['d1'])[0];
?>

<table width="100%">
	<thead>
		<tr>
			<td style="font-weight: 800">Analisa Aset Terhadap Ekuitas</td>
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
					<th width="20%" style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white;">Periode</th>
					<th width="20%" style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white;">Nilai Ekuitas</th>
					<th width="20%" style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white;">Nilai Akhir Aset</th>
					<th width="20%" style="text-align: center; border: 1px solid #eee; background-color: #0099CC; color: white;">Nilai Ekuitas/Aset</th>
				</tr>
			</thead>
				
			<tbody>
				@foreach($content['data'] as $key => $sdf)
					<tr>
						<td style="font-weight: 600; text-align: center; border: 1px solid #eee; padding: 5px;">
							{{ date('m/Y', strtotime($sdf['tanggal'])) }}
						</td>

						<td style="font-weight: 600; text-align: right;">
							{{ number_format(($sdf['ekuitas']->saldo_awal + $sdf['ekuitas']->penambahan) - $sdf['ekuitas']->pengurangan, 2) }}
						</td>

						<td style="font-weight: 600; text-align: right;">
							{{ number_format(($sdf['aset']->saldo_awal + $sdf['aset']->penambahan) - $sdf['aset']->pengurangan, 2) }}
						</td>

						<?php 
							if(($sdf['ekuitas']->saldo_awal + $sdf['ekuitas']->penambahan) - $sdf['ekuitas']->pengurangan != 0){
								$persen = ((($sdf['ekuitas']->saldo_awal + $sdf['ekuitas']->penambahan) - $sdf['ekuitas']->pengurangan) / (($sdf['aset']->saldo_awal + $sdf['aset']->penambahan) - $sdf['aset']->pengurangan)) * 100;
							}else{
								$persen = 0;
							}
						?>

						<td style="font-weight: 600; text-align: center;">
							{{ number_format($persen, 2) }} %
						</td>

						
					</tr>
				@endforeach
			</tbody>
		</table>

<script type="text/javascript">
	window.print()
</script>
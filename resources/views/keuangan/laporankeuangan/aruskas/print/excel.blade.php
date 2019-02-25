<table>
	<thead>
		<tr></tr>

		<?php 
			$tanggal_1 = explode('/', $data['request']['d1'])[0].' '.switchBulan(explode('/', $data['request']['d1'])[1]).' '.explode('/', $data['request']['d1'])[2];

			$tanggal_2 = explode('/', $data['request']['d2'])[0].' '.switchBulan(explode('/', $data['request']['d2'])[1]).' '.explode('/', $data['request']['d2'])[2];

			$type = 'Transaksi Kas';

			if($data['request']['type'] == 'B')
				$type = 'Transaksi Bank';
			else if($data['request']['type'] == 'M')
				$type = 'Transaksi Memorial'
		?>

		<tr>
			<td></td>
			@if($data['request']['nama'] == 'true')
				<td colspan="7" style="background-color: none;">
					<b>Laporan Jurnal {{ $type }}</b>
				</td>
			@else
				<td colspan="6" style="background-color: none;">
					<b>Laporan Jurnal {{ $type }}</b>
				</td>
			@endif
		</tr>

		<tr>
			<td></td>
			@if($data['request']['nama'] == 'true')
				<td colspan="7" style="background-color: none;">
					<b>{{ jurnal()->companyName }}</b>
				</td>
			@else
				<td colspan="6" style="background-color: none;">
					<b>{{ jurnal()->companyName }}</b>
				</td>
			@endif
		</tr>

		<tr>
			<td></td>
			@if($data['request']['nama'] == 'true')
				<td colspan="7" style="background-color: none;">
					<b>{{ $tanggal_1 }}</b>&nbsp; s/d&nbsp; <b>{{ $tanggal_2 }}</b>
				</td>
			@else
				<td colspan="6" style="background-color: none;">
					<b>{{ $tanggal_1 }}</b>&nbsp; s/d&nbsp; <b>{{ $tanggal_2 }}</b>
				</td>
			@endif
		</tr>

		<tr></tr>
		<tr></tr>

		<tr>
			<th></th>

			<th style="background-color: #0099CC; color: #ffffff;">Tanggal</th>
			<th style="background-color: #0099CC; color: #ffffff;">No. Bukti</th>

			@if($data['request']['nama'] == 'true')
				<th style="background-color: #0099CC; color: #ffffff;">Keterangan</th>
				<th style="background-color: #0099CC; color: #ffffff;">No. Akun</th>
				<th style="background-color: #0099CC; color: #ffffff;">Nama Akun</th>
			@else
				<th style="background-color: #0099CC; color: #ffffff;">Keterangan</th>
				<th style="background-color: #0099CC; color: #ffffff;">No. Akun</th>
			@endif
	
			<th style="background-color: #0099CC; color: #ffffff;">Debet</th>
			<th style="background-color: #0099CC; color: #ffffff;">Kredit</th>
		</tr>
	</thead>

	<tbody>
		<?php $D = $K = 0 ?>
		@foreach($data['data'] as $key => $resource)
			<?php $DS = $KS = 0; ?>
			@foreach($resource->detail as $xys => $detail)
				<tr>
					<td></td>
					<td>{{ date('d/m/Y', strtotime($resource->jr_tanggal_trans)) }}</td>
					<td>{{ $resource->jr_ref }}</td>
					<td>{{ $resource->jr_keterangan }}</td>
					<td>{{ $detail->jrdt_akun }}</td>

					@if($data['request']['nama'] == 'true')
						<td>{{ $detail->ak_nama }}</td>
					@endif

					<td>{{ ($detail->jrdt_dk == 'D') ? $detail->jrdt_value : number_format(0, 2) }}</td>
					<td>{{ ($detail->jrdt_dk == 'K') ? $detail->jrdt_value : number_format(0, 2) }}</td>

					<?php 
						if($detail->jrdt_dk == "D"){
							$DS += $detail->jrdt_value;
							$D += $detail->jrdt_value;
						}
						else{
							$KS += $detail->jrdt_value;
							$K += $detail->jrdt_value;
						}
					?>
				</tr>
			@endforeach

			<tr>
				<td></td>

				@if($data['request']['nama'] == 'true')
					<td colspan="5" style="background-color: #eee"></td>
				@else
					<td colspan="4" style="background-color: #eee"></td>
				@endif

				<td style="background-color: #eee;">{{ $DS }}</td>
				<td style="background-color: #eee;">{{ $KS }}</td>
			</tr>
		@endforeach

		<tr>
			<td></td>

			@if($data['request']['nama'] == 'true')
				<td colspan="5" style="background-color: none"></td>
			@else
				<td colspan="4" style="background-color: none"></td>
			@endif

			<td style="background-color: #0d47a1; color: #ffffff;">{{ $D }}</td>
			<td style="background-color: #007E33; color: #ffffff;">{{ $K }}</td>
		</tr>
	</tbody>
</table>
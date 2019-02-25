<?php 
	$tanggal_1 = switchBulan(explode('/', $_GET['d1'])[0]).' '.explode('/', $_GET['d1'])[1];
?>

<table width="100%">
	<thead>
		<tr>
			<td></td>
			<td style="font-weight: 800">Laporan Neraca</td>
		</tr>

		<tr>
			<td></td>
			<td>{{ jurnal()->companyName }}</td>
		</tr>

		<tr>
			<td></td>
			<td style="border-bottom: 1px solid #ccc; padding-bottom: 20px;"><small>Bulan {{ $tanggal_1 }}</small></td>
		</tr>
	</thead>
</table>

<br>
	
	{{-- @if($_GET['tampilan'] == 'tabular') --}}
		<table width="100%" style="font-size: 9pt;">
			<tbody>
				<tr>
					<td></td>
					<td width="50%" style="padding: 5px; text-align: center; border: 1px solid #ccc;">Aktiva</td>
					<td width="50%" style="padding: 5px; text-align: center; border: 1px solid #ccc;">Pasiva</td>
				</tr>

				<tr>
					<td></td>
					<td style="vertical-align: top;">
						<table width="100%">
							<?php $aktiva = $pasiva = 0 ?>
							@foreach($data['data'] as $key => $header)
								@if($header->hls_id == '1')
									<?php $totLevel1 = 0 ?>
									<tr>
										<td colspan="2" style="border: 1px solid #ccc; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; background-color: #0099cc; color: #ffffff">{{ $header->hls_nama }}</td>
									</tr>
		 
									@foreach($header->subclass as $key => $group)
										<?php $totSubclass = 0 ?>

										@if($group->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="border: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px; background-color: #555555; color: #ffffff">{{ $group->hs_nama }}</td>

												<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px;"></td>
											</tr>
											@endif

										@foreach($group->level_2 as $key => $lvl_2)

											<?php 
												$margin = ($group->hs_nama != 'Tidak Memiliki') ? "40px" : "20px";
												$dif = 0;

												foreach($lvl_2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir; 
												}

												$totSubclass += $dif;

											?>

											<tr>
												<td style="border: 1px solid #ccc; padding-left: {{ $margin }}; padding-top: 5px; padding-bottom: 5px;">{{ $lvl_2->hld_nama }}</td>

												<td style="font-weight: normal; border: 1px solid #cccccc; text-align: right; padding: 5px;">{{ $dif }}</td>
											</tr>

										@endforeach

										<?php $totLevel1 += $totSubclass; ?>

										@if($group->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="border: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px; background-color: #555555; color: #ffffff">Total {{ $group->hs_nama }}</td>

												<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px;">{{ $totSubclass }}</td>
											</tr>
										@endif

									@endforeach

									<tr>
										<td style="border: 1px solid #ccc; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; background-color: #eeeeee;">Total {{ $header->hls_nama }}</td>

										<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px; background-color: #eeeeee;">{{ $totLevel1 }}</td>
										
									</tr>

									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>

									<?php $aktiva += $totLevel1 ?>
								@endif
							@endforeach
						</table>
					</td>
				<tr>

				<tr>
					<td></td>
					<td style="vertical-align: top;">
						<table width="100%">
							@foreach($data['data'] as $key => $header)
								@if($header->hls_id != '1')
									<?php $totLevel1 = 0 ?>
									<tr>
										<td colspan="2" style="border: 1px solid #ccc; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; background-color: #0099cc; color: #ffffff">{{ $header->hls_nama }}</td>
									</tr>
		 
									@foreach($header->subclass as $key => $group)
										<?php $totSubclass = 0 ?>

										@if($group->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="border: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px; background-color: #555555; color: #ffffff">{{ $group->hs_nama }}</td>

												<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px;"></td>
											</tr>
											@endif

										@foreach($group->level_2 as $key => $lvl_2)

											<?php 
												$margin = ($group->hs_nama != 'Tidak Memiliki') ? "40px" : "20px";
												$dif = 0;

												foreach($lvl_2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir; 
												}

												$totSubclass += $dif;

											?>

											<tr>
												<td style="border: 1px solid #ccc; padding-left: {{ $margin }}; padding-top: 5px; padding-bottom: 5px;">{{ $lvl_2->hld_nama }}</td>

												<td style="font-weight: normal; border: 1px solid #cccccc; text-align: right; padding: 5px;">{{ $dif }}</td>
											</tr>

										@endforeach

										<?php $totLevel1 += $totSubclass; ?>

										@if($group->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="border: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px; background-color: #555555; color: #ffffff">Total {{ $group->hs_nama }}</td>

												<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px;">{{ $totSubclass }}</td>
											</tr>
										@endif

									@endforeach

									<tr>
										<td style="border: 1px solid #ccc; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; background-color: #eeeeee;">Total {{ $header->hls_nama }}</td>

										<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px; background-color: #eeeeee;">{{ $totLevel1 }}</td>
										
									</tr>

									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>

									<?php $pasiva += $totLevel1 ?>
								@endif
							@endforeach
						</table>
					</td>
				</tr>

				<tr>
					<td></td>
					<td>
						<table width="100%">
							<tr>
								<td style="border: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px;">Total Aktiva</td>

								<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px;">{{ $aktiva }}</td>
							</tr>
						</table>
					</td>
				<tr>

				<tr>
					<td></td>
					<td>
						<table width="100%">
							<tr>
								<td style="border: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px;">Total Kewajiban + Aktiva</td>

								<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px;">{{ $pasiva }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	{{-- @endif --}}
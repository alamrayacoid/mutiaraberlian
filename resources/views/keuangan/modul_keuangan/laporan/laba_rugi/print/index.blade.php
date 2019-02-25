<style type="text/css" media="print">
    @page { size: portrait; }
</style>

<?php 
	$tanggal_1 = switchBulan(explode('/', $_GET['d1'])[0]).' '.explode('/', $_GET['d1'])[1];
?>

<table width="100%">
	<thead>
		<tr>
			<td style="font-weight: 800">Laporan Laba Rugi</td>
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
		<table width="100%" style="font-size: 9pt;">
			<tbody>
				<tr>
					<td style="vertical-align: top;">
						<table width="100%">
							<?php $dataSum = []; ?>
							@foreach($data['data'] as $key => $level1)
								@if($level1->hls_id == '4' || $level1->hls_id == '5')
									<?php $totalLevel1 = 0 ?>
									<tr>
										<td style="padding-left: 10px; font-weight: bold;">{{ $level1->hls_nama }}</td>
										<td></td>
									</tr>

									@foreach($level1->subclass as $key => $subclass)
										@if($subclass->hs_nama != 'Tidak Memiliki')
											<tr>
												<td style="padding-left: 30px; font-weight: lighter; font-style: italic;">
													{{ $subclass->hs_nama }}
												</td>
												<td></td>
											</tr>
										@endif

										@foreach($subclass->level_2 as $key => $level2)
											<?php 
												$margin = ($subclass->hs_nama != 'Tidak Memiliki') ? "50px" : "30px";
												$bold = ($subclass->hs_nama != 'Tidak Memiliki') ? "600px" : "lighter";
												$dif = 0;

												foreach($level2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir; 
												}

												$totalLevel1 += $dif

											?>
											<tr>
												<td style="padding-left: {{ $margin }}; font-weight: {{ $bold }};">
													{{ $level2->hld_nama }}
												</td>
												<td style="text-align: right; padding-right: 10px;">
													{{ ($dif < 0) ? '('.number_format(str_replace('-', '', $dif), 2).')' : number_format($dif, 2) }}
												</td>
											</tr>

										@endforeach

										{{-- @if($subclass->hs_nama != 'Tidak Memiliki')
											<tr>
												<td style="padding-left: 30px; font-weight: lighter; font-style: italic;">
													Total {{ $subclass->hs_nama }}
												</td>
												<td></td>
											</tr>
										@endif --}}

									@endforeach
										<tr>
											<td style="padding-left: 10px; font-weight: bold;">
												Total {{ $level1->hls_nama }}
											</td>
											<td style="border-top: 1px solid #ccc; font-weight: bold; text-align: right; padding-right: 10px;">
												{{ ($totalLevel1 < 0) ? '('.number_format(str_replace('-', '', $totalLevel1), 2).')' : number_format($totalLevel1, 2) }}
											</td>
										</tr>

										<tr><td>&nbsp;</td></tr>

										<?php $dataSum[$level1->hls_id] = $totalLevel1; ?>
								@endif
							@endforeach

							<tr>
								<td style="padding-left: 20px; font-weight: bold; color: #0099CC">
									Laba Rugi Kotor
								</td>
								<td style="padding-right: 10px; font-weight: bold; color: #0099CC; text-align: right;">
									{{ (($dataSum[4] - $dataSum[5]) < 0) ? '('.number_format(str_replace('-', '', ($dataSum[4] - $dataSum[5])), 2) : number_format(($dataSum[4] - $dataSum[5]), 2) }}
								</td>
							</tr>

							<tr><td>&nbsp;</td></tr>

							@foreach($data['data'] as $key => $level1)
								@if($level1->hls_id == '6' || $level1->hls_id == '7')
									<?php $top = 0; ?>
									<tr>
										<td style="padding-left: 10px; font-weight: bold;">{{ $level1->hls_nama }}</td>
										<td></td>
									</tr>

									@foreach($level1->subclass as $key => $subclass)
										@if($subclass->hs_nama != 'Tidak Memiliki')
											<tr>
												<td style="padding-left: 30px; font-weight: lighter; font-style: italic;">
													{{ $subclass->hs_nama }}
												</td>
												<td></td>
											</tr>
										@endif

										@foreach($subclass->level_2 as $key => $level2)
											<?php 
												$margin = ($subclass->hs_nama != 'Tidak Memiliki') ? "50px" : "30px";
												$bold = ($subclass->hs_nama != 'Tidak Memiliki') ? "600px" : "lighter";
												$dif = 0;

												foreach($level2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir;
												}

												$top += $dif

											?>
											<tr>
												<td style="padding-left: {{ $margin }}; font-weight: {{ $bold }};">
													{{ $level2->hld_nama }}
												</td>
												<td style="text-align: right; padding-right: 10px;">
													{{ ($dif < 0) ? '('.number_format(str_replace('-', '', $dif), 2).')' : number_format($dif, 2) }}
												</td>
											</tr>

										@endforeach

										{{-- @if($subclass->hs_nama != 'Tidak Memiliki')
											<tr>
												<td style="padding-left: 30px; font-weight: lighter; font-style: italic;">
													Total {{ $subclass->hs_nama }}
												</td>
												<td></td>
											</tr>
										@endif --}}

									@endforeach
										<tr>
											<td style="padding-left: 10px; font-weight: bold;">
												Total {{ $level1->hls_nama }}
											</td>
											<td style="border-top: 1px solid #ccc; font-weight: bold; text-align: right; padding-right: 10px;">
												{{ ($top < 0) ? '('.number_format(str_replace('-', '', $top), 2).')' : number_format($top, 2) }}
											</td>
										</tr>

										<tr><td>&nbsp;</td></tr>

										<?php $dataSum[$level1->hls_id] = $top; ?>
								@endif
							@endforeach

							<tr>
								<td style="padding-left: 20px; font-weight: bold; color: #0099CC">
									Total Beban Operasi & Administrasi Umum
								</td>
								<td style="padding-right: 10px; font-weight: bold; color: #0099CC; text-align: right;">
									{{ (($dataSum[6] + $dataSum[7]) < 0) ? '('.number_format(str_replace('-', '', ($dataSum[6] + $dataSum[7])), 2) : number_format(($dataSum[6] + $dataSum[7]), 2) }}
								</td>
							</tr>

							<tr>
								<td style="padding-left: 20px; font-weight: bold; color: #0099CC">
									Laba Operasi
								</td>
								<td style="padding-right: 10px; font-weight: bold; color: #0099CC; text-align: right;">
									{{ (($dataSum[4] - $dataSum[5]) - ($dataSum[6] + $dataSum[7]) < 0) ? '('.number_format(str_replace('-', '', ($dataSum[4] - $dataSum[5]) - ($dataSum[6] + $dataSum[7])), 2).')' : number_format(($dataSum[4] - $dataSum[5]) - ($dataSum[6] + $dataSum[7]), 2) }}
								</td>
							</tr>

							<tr><td>&nbsp;</td></tr>

							@foreach($data['data'] as $key => $level1)
								@if($level1->hls_id == '8' || $level1->hls_id == '9')
									<?php $top = 0; ?>
									<tr>
										<td style="padding-left: 10px; font-weight: bold;">{{ $level1->hls_nama }}</td>
										<td></td>
									</tr>

									@foreach($level1->subclass as $key => $subclass)
										@if($subclass->hs_nama != 'Tidak Memiliki')
											<tr>
												<td style="padding-left: 30px; font-weight: lighter; font-style: italic;">
													{{ $subclass->hs_nama }}
												</td>
												<td></td>
											</tr>
										@endif

										@foreach($subclass->level_2 as $key => $level2)
											<?php 
												$margin = ($subclass->hs_nama != 'Tidak Memiliki') ? "50px" : "30px";
												$bold = ($subclass->hs_nama != 'Tidak Memiliki') ? "600px" : "lighter";
												$dif = 0;

												foreach($level2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir;
												}

												$top += $dif

											?>
											<tr>
												<td style="padding-left: {{ $margin }}; font-weight: {{ $bold }};">
													{{ $level2->hld_nama }}
												</td>
												<td style="text-align: right; padding-right: 10px;">
													{{ ($dif < 0) ? '('.number_format(str_replace('-', '', $dif), 2).')' : number_format($dif, 2) }}
												</td>
											</tr>

										@endforeach

										{{-- @if($subclass->hs_nama != 'Tidak Memiliki')
											<tr>
												<td style="padding-left: 30px; font-weight: lighter; font-style: italic;">
													Total {{ $subclass->hs_nama }}
												</td>
												<td></td>
											</tr>
										@endif --}}

									@endforeach
										<tr>
											<td style="padding-left: 10px; font-weight: bold;">
												Total {{ $level1->hls_nama }}
											</td>
											<td style="border-top: 1px solid #ccc; font-weight: bold; text-align: right; padding-right: 10px;">
												{{ ($top < 0) ? '('.number_format(str_replace('-', '', $top), 2).')' : number_format($top, 2) }}
											</td>
										</tr>

										<tr><td>&nbsp;</td></tr>

										<?php $dataSum[$level1->hls_id] = $top; ?>
								@endif
							@endforeach

							<tr>
								<td style="padding-left: 20px; font-weight: bold; color: #0099CC">
									Laba Sebelum Pajak
								</td>
								<td style="padding-right: 10px; font-weight: bold; color: #0099CC; text-align: right;">
									{{ (($dataSum[4] - $dataSum[5]) - ($dataSum[6] + $dataSum[7]) + ($dataSum[8] - $dataSum[9]) < 0) ? '('.number_format(str_replace('-', '', ($dataSum[4] - $dataSum[5]) - ($dataSum[6] + $dataSum[7]) + ($dataSum[8] - $dataSum[9])), 2).')' : number_format(($dataSum[4] - $dataSum[5]) - ($dataSum[6] + $dataSum[7]) + ($dataSum[8] - $dataSum[9]), 2) }}
								</td>
							</tr>

						</table>
					</td>
				</tr>

				<tr>
					<td>
						{{-- <table width="100%">
							<tr>
								<td style="border-bottom: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px;">Total Pendapatan</td>

								<td style="font-weight: 800; border-bottom: 1px solid #cccccc; text-align: right; padding: 5px;"></td>
							</tr>
						</table> --}}
					</td>
				</tr>
			</tbody>
		</table>

<script type="text/javascript">
	window.print()
</script>
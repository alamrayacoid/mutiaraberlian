<style type="text/css" media="print">
    @page { size: landscape; }
</style>

<?php 
	$tanggal_1 = switchBulan(explode('/', $_GET['d1'])[0]).' '.explode('/', $_GET['d1'])[1];
?>

<table width="100%">
	<thead>
		<tr>
			<td style="font-weight: 800">Laporan Neraca</td>
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
	
	@if($_GET['tampilan'] == 'tabular')
		<table width="100%" style="font-size: 9pt;">
			<tbody>
				<tr>
					<td width="50%" style="padding: 5px; text-align: center; border: 1px solid #ccc;">Aktiva</td>
					<td width="50%" style="padding: 5px; text-align: center; border: 1px solid #ccc;">Pasiva</td>
				</tr>

				<tr>
					<td style="vertical-align: top;">
						<table width="100%">
							<?php $aktiva = $pasiva = 0 ?>
							@foreach($data as $a => $level_1)
								@if($level_1->hls_id == '1')
									<?php $totLevel1 = 0; ?>
									<tr>
										<td width="70%" style="font-weight: bold;">
											{{ $level_1->hls_nama }}
										</td>

										<td width="30%" style="font-weight: bold;">
											
										</td>
									</tr>

									@foreach($level_1->subclass as $a => $subclass)
										<?php $totSubclass = 0; ?>
										@if($subclass->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="padding-left: 25px; font-style: italic;">{{ $subclass->hs_nama }}</td>
												<td></td>
											</tr>
										@endif

										@foreach($subclass->level_2 as $a => $level2)
											<?php 
												$margin = ($subclass->hs_nama != 'Tidak Memiliki') ? "50px" : "25px";
												$dif = 0;

												foreach($level2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir; 
												}

												$totSubclass += $dif;

											?>
											<tr>
												<td style="padding-left: {{ $margin }}; font-weight: normal;">{{ $level2->hld_nama }}</td>
												<td style=" text-align: right;">
													{{ ($dif < 0 )? '('.number_format(str_replace('-', '', $dif), 2).')' : number_format($dif, 2) }}
												</td>
											</tr>

										@endforeach

										<?php $totLevel1 += $totSubclass; ?>

										@if($subclass->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="padding-left: 25px; font-weight: 600;">Total {{ $subclass->hs_nama }}</td>
												<td style="border-top: 1px solid #eee; text-align: right; font-weight: 600;">
													{{ ($totSubclass < 0 )? '('.number_format(str_replace('-', '', $totSubclass), 2).')' : number_format($totSubclass, 2) }}
												</td>
											</tr>
										@endif

										<tr><td colspan="2">&nbsp;</td></tr>

									@endforeach
									<?php $aktiva += $totLevel1; ?>
									<tr>
										<td width="70%" style="font-weight: bold;">
											Total {{ $level_1->hls_nama }}
										</td>

										<td width="30%" style="font-weight: bold; text-align: right;">
											{{ ($totLevel1 < 0 )? '('.number_format(str_replace('-', '', $totLevel1), 2).')' : number_format($totLevel1, 2) }}
										</td>
									</tr>

									<tr><td colspan="2">&nbsp;</td></tr>
								@endif
							@endforeach
						</table>
					</td>

					<td style="vertical-align: top;">
						<table width="100%">

							@foreach($data as $a => $level_1)
								@if($level_1->hls_id != '1')
									<?php $totLevel1 = 0; ?>
									<tr>
										<td width="70%" style="font-weight: bold; padding-left: 20px;">
											{{ $level_1->hls_nama }}
										</td>

										<td width="30%" style="font-weight: bold;">
											
										</td>
									</tr>

									@foreach($level_1->subclass as $a => $subclass)
										<?php $totSubclass = 0; ?>
										@if($subclass->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="padding-left: 25px; font-style: italic; padding-left: 20px;">{{ $subclass->hs_nama }}</td>
												<td></td>
											</tr>
										@endif

										@foreach($subclass->level_2 as $a => $level2)
											<?php 
												$margin = ($subclass->hs_nama != 'Tidak Memiliki') ? "50px" : "25px";
												$dif = 0;

												foreach($level2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir; 
												}

												$totSubclass += $dif;

											?>
											<tr>
												<td style="padding-left: {{ $margin }}; font-weight: normal; padding-left: 20px;">{{ $level2->hld_nama }}</td>
												<td style=" text-align: right;">
													{{ ($dif < 0 )? '('.number_format(str_replace('-', '', $dif), 2).')' : number_format($dif, 2) }}
												</td>
											</tr>

										@endforeach

										<?php $totLevel1 += $totSubclass; ?>

										@if($subclass->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="padding-left: 25px; font-weight: 600;">Total {{ $subclass->hs_nama }}</td>
												<td style="border-top: 1px solid #eee; text-align: right; font-weight: 600; padding-left: 20px;">
													{{ ($totSubclass < 0 )? '('.number_format(str_replace('-', '', $totSubclass), 2).')' : number_format($totSubclass, 2) }}
												</td>
											</tr>
										@endif

										<tr><td colspan="2">&nbsp;</td></tr>

									@endforeach
									<?php $pasiva += $totLevel1; ?>
									<tr>
										<td width="70%" style="font-weight: bold; padding-left: 20px;">
											Total {{ $level_1->hls_nama }}
										</td>

										<td width="30%" style="font-weight: bold; text-align: right;">
											{{ ($totLevel1 < 0 )? '('.number_format(str_replace('-', '', $totLevel1), 2).')' : number_format($totLevel1, 2) }}
										</td>
									</tr>

									<tr><td colspan="2">&nbsp;</td></tr>
								@endif
							@endforeach
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table width="100%">
							<tr>
								<td style="border-bottom: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px;">Total Aktiva</td>

								<td style="font-weight: 800; border-bottom: 1px solid #cccccc; text-align: right; padding: 5px;">{{ ($aktiva < 0 )? '('.number_format(str_replace('-', '', $aktiva), 2).')' : number_format($aktiva, 2) }}</td>
							</tr>
						</table>
					</td>

					<td>
						<table width="100%">
							<tr>
								<td style="border-bottom: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px;">Total Kewajiban + Modal</td>

								<td style="font-weight: 800; border-bottom: 1px solid #cccccc; text-align: right; padding: 5px;">{{ ($pasiva < 0 )? '('.number_format(str_replace('-', '', $pasiva), 2).')' : number_format($pasiva, 2) }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	@endif

	@if($_GET['tampilan'] == 'menurun')
		<table width="100%" style="font-size: 9pt;">
			<tbody>
				<tr>
					<td style="padding: 5px; text-align: center; border-bottom: 1px solid #ccc;">Aktiva</td>
				</tr>

				<tr>
					<td style="vertical-align: top;">
						<table width="100%">
							<?php $aktiva = $pasiva = 0 ?>
							@foreach($data as $a => $level_1)
								@if($level_1->hls_id == '1')
									<?php $totLevel1 = 0; ?>
									<tr>
										<td width="70%" style="font-weight: bold; padding-left: 10px;">
											{{ $level_1->hls_nama }}
										</td>

										<td width="30%" style="font-weight: bold;">
											
										</td>
									</tr>

									@foreach($level_1->subclass as $a => $subclass)
										<?php $totSubclass = 0; ?>
										@if($subclass->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="padding-left: 30px; font-style: italic;">{{ $subclass->hs_nama }}</td>
												<td></td>
											</tr>
										@endif

										@foreach($subclass->level_2 as $a => $level2)
											<?php 
												$margin = ($subclass->hs_nama != 'Tidak Memiliki') ? "50px" : "25px";
												$dif = 0;

												foreach($level2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir; 
												}

												$totSubclass += $dif;

											?>
											<tr>
												<td style="padding-left: {{ $margin }}; font-weight: normal;">{{ $level2->hld_nama }}</td>
												<td style=" text-align: right;">
													{{ ($dif < 0 )? '('.number_format(str_replace('-', '', $dif), 2).')' : number_format($dif, 2) }}
												</td>
											</tr>

										@endforeach

										<?php $totLevel1 += $totSubclass; ?>

										@if($subclass->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="padding-left: 25px; font-weight: 600;">Total {{ $subclass->hs_nama }}</td>
												<td style="border-top: 1px solid #eee; text-align: right; font-weight: 600; padding-left: 20px;">
													{{ ($totSubclass < 0 )? '('.number_format(str_replace('-', '', $totSubclass), 2).')' : number_format($totSubclass, 2) }}
												</td>
											</tr>
										@endif

										<tr><td colspan="2">&nbsp;</td></tr>

									@endforeach
									<?php $aktiva += $totLevel1; ?>
									<tr>
										<td width="70%" style="font-weight: bold; padding-left: 10px;">
											Total {{ $level_1->hls_nama }}
										</td>

										<td width="30%" style="font-weight: bold; text-align: right;">
											{{ ($totLevel1 < 0 )? '('.number_format(str_replace('-', '', $totLevel1), 2).')' : number_format($totLevel1, 2) }}
										</td>
									</tr>

									<tr><td colspan="2">&nbsp;</td></tr>
								@endif
							@endforeach
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table width="100%">
							<tr>
								<td style="border-bottom: 1px solid #ccc; padding-left: 10px; padding-top: 5px; padding-bottom: 5px;">Total Aktiva</td>

								<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px;">{{ number_format($aktiva, 2) }}</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td style="padding: 5px; text-align: center; border-bottom: 1px solid #ccc;">Pasiva</td>
				</tr>

				<tr>
					<td style="vertical-align: top;">
						<table width="100%">

							@foreach($data as $a => $level_1)
								@if($level_1->hls_id != '1')
									<?php $totLevel1 = 0; ?>
									<tr>
										<td width="70%" style="font-weight: bold; padding-left: 10px;">
											{{ $level_1->hls_nama }}
										</td>

										<td width="30%" style="font-weight: bold;">
											
										</td>
									</tr>

									@foreach($level_1->subclass as $a => $subclass)
										<?php $totSubclass = 0; ?>
										@if($subclass->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="padding-left: 30px; font-style: italic;">{{ $subclass->hs_nama }}</td>
												<td></td>
											</tr>
										@endif

										@foreach($subclass->level_2 as $a => $level2)
											<?php 
												$margin = ($subclass->hs_nama != 'Tidak Memiliki') ? "50px" : "25px";
												$dif = 0;

												foreach($level2->akun as $alpha => $akun){
													$dif += $akun->saldo_akhir; 
												}

												$totSubclass += $dif;

											?>
											<tr>
												<td style="padding-left: {{ $margin }}; font-weight: normal;">{{ $level2->hld_nama }}</td>
												<td style=" text-align: right;">
													{{ ($dif < 0 )? '('.number_format(str_replace('-', '', $dif), 2).')' : number_format($dif, 2) }}
												</td>
											</tr>

										@endforeach

										<?php $totLevel1 += $totSubclass; ?>

										@if($subclass->hs_nama != "Tidak Memiliki")
											<tr>
												<td style="padding-left: 25px; font-weight: 600;">Total {{ $subclass->hs_nama }}</td>
												<td style="border-top: 1px solid #eee; text-align: right; font-weight: 600; padding-left: 20px;">
													{{ ($totSubclass < 0 )? '('.number_format(str_replace('-', '', $totSubclass), 2).')' : number_format($totSubclass, 2) }}
												</td>
											</tr>
										@endif

										<tr><td colspan="2">&nbsp;</td></tr>

									@endforeach
									<?php $pasiva += $totLevel1; ?>
									<tr>
										<td width="70%" style="font-weight: bold; padding-left: 10px;">
											Total {{ $level_1->hls_nama }}
										</td>

										<td width="30%" style="font-weight: bold; text-align: right;">
											{{ ($totLevel1 < 0 )? '('.number_format(str_replace('-', '', $totLevel1), 2).')' : number_format($totLevel1, 2) }}
										</td>
									</tr>

									<tr><td colspan="2">&nbsp;</td></tr>
								@endif
							@endforeach
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table width="100%">
							<tr>
								<td style="border-bottom: 1px solid #ccc; padding-left: 20px; padding-top: 5px; padding-bottom: 5px;">Total Kewajiban + Modal</td>

								<td style="font-weight: 800; border: 1px solid #cccccc; text-align: right; padding: 5px;">{{ number_format($pasiva, 2) }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	@endif

<script type="text/javascript">
	window.print()
</script>